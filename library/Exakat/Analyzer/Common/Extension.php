<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Exakat\Analyzer\Common;

use Exakat\Analyzer\Analyzer;

class Extension extends Analyzer {
    protected $source = '';

    public function dependsOn(): array {
        return array('Classes/ClassUsage',
                     'Interfaces/InterfaceUsage',
                     'Traits/TraitUsage',
                     'Constants/ConstantUsage',
                     'Namespaces/NamespaceUsage',
                     'Php/DirectivesUsage',
                     'Complete/PropagateCalls',
                     );
    }


    public function analyze(): void {
        if (empty($this->source)) {
            return;
        }

        if (substr($this->source, -4) === '.ini') {
            $ini = (object) $this->loadIni($this->source);
        } elseif (substr($this->source, -5) === '.json') {
            $ini = $this->loadJson($this->source);
        } else {
            return ;
        }

        if (!empty($ini->functions)) {
            $functions = makeFullNsPath($ini->functions);
            $this->atomFunctionIs($functions);
            $this->prepareQuery();
        }

        if (!empty($ini->constants)) {
            $this->atomIs(self::STATIC_NAMES)
                 ->analyzerIs('Constants/ConstantUsage')
                 ->fullnspathIs(makeFullNsPath($ini->constants, \FNP_CONSTANT));
            $this->prepareQuery();
        }

        if (!empty($ini->classes)) {
            $classes = makeFullNsPath($ini->classes);

            $usedClasses = array_intersect(self::getCalledClasses(), $classes);
            if (!empty($usedClasses)) {
                $usedClasses = array_values($usedClasses);
                $this->atomIs('New')
                     ->outIs('NEW')
                     ->hasNoIn('DEFINITION')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();

                $this->atomIs(array('Staticconstant', 'Staticmethodcall', 'Staticproperty'))
                     ->outIs('CLASS')
                     ->hasNoIn('DEFINITION')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();

                $this->atomIs(self::FUNCTIONS_ALL)
                     ->outIs('ARGUMENT')
                     ->outIs('TYPEHINT')
                     ->hasNoIn('DEFINITION')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();

                $this->atomIs(self::FUNCTIONS_ALL)
                     ->outIs('RETURNTYPE')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();

                $this->atomIs('Catch')
                     ->outIs('CLASS')
                     ->hasNoIn('DEFINITION')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();

                $this->atomIs('Instanceof')
                     ->outIs('CLASS')
                     ->hasNoIn('DEFINITION')
                     ->fullnspathIs($usedClasses);
                $this->prepareQuery();
            }
        }

        if (!empty($ini->interfaces)) {
            $interfaces = makeFullNsPath($ini->interfaces);

            $usedInterfaces = array_intersect(self::getCalledinterfaces(), $interfaces);

            if (!empty($usedInterfaces)) {
                $usedInterfaces = array_values($usedInterfaces);
                $this->analyzerIs('Interfaces/InterfaceUsage')
                     ->fullnspathIs($usedInterfaces);
                $this->prepareQuery();
            }
        }

        if (!empty($ini->traits)) {
            $traits = makeFullNsPath($ini->traits);

            $usedTraits = array_intersect(self::getCalledtraits(), $traits);

            if (!empty($usedTraits)) {
                $usedTraits = array_values($usedTraits);
                $this->analyzerIs('Traits/TraitUsage')
                     ->fullnspathIs($usedTraits);
                $this->prepareQuery();
            }
        }

        if (!empty($ini->namespaces)) {
            $namespaces = makeFullNsPath($ini->namespaces);

            $usedNamespaces = array_intersect($this->getCalledNamespaces(), $namespaces);

            if (!empty($usedNamespaces)) {
                $usedNamespaces = array_values($usedNamespaces);
                $this->analyzerIs('Namespaces/NamespaceUsage')
                     ->fullnspathIs($usedNamespaces);
                $this->prepareQuery();
            }
        }

        if (!empty($ini->directives)) {
            $usedDirectives = array_intersect(self::getCalledDirectives(), $ini->directives);

            if (!empty($usedDirectives)) {
                $usedDirectives = array_values($usedDirectives);
                $this->analyzerIs('Php/DirectivesUsage')
                     ->outWithRank('ARGUMENT', 0)
                     ->noDelimiterIs($usedDirectives, self::CASE_SENSITIVE);
                $this->prepareQuery();
            }
        }

        // json only
        if (!empty($ini->classconstants)) {
            $classesconstants = (array) $ini->classconstants;
            $this->atomIs('Staticconstant')
                 ->outIs('CLASS')
                 ->fullnspathIs(array_keys($classesconstants))
                 ->savePropertyAs('fullnspath', 'fqn')
                 ->back('first')

                 ->outIs('CONSTANT')
                 ->isHash('fullcode', $classesconstants, 'fqn', self::CASE_SENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }

        if (!empty($ini->methods)) {
            $methods = (array) $ini->methods;

            $this->atomIs('Staticmethodcall')
                 ->outIs('CLASS')
                 ->fullnspathIs(array_keys($methods))
                 ->savePropertyAs('fullnspath', 'fqn')
                 ->back('first')

                 ->outIs('METHOD')
                 ->outIs('NAME')
                 ->tokenIs('T_STRING')
                 ->isHash('fullcode', $methods, 'fqn', self::CASE_INSENSITIVE)
                 ->back('first');
            $this->prepareQuery();

            $this->atomIs('Methodcall')
                 ->outIs('OBJECT')
                 ->fullnspathIs(array_keys($methods))
                 ->savePropertyAs('fullnspath', 'fqn')
                 ->back('first')

                 ->outIs('METHOD')
                 ->outIs('NAME')
                 ->tokenIs('T_STRING')
                 ->isHash('fullcode', $methods, 'fqn', self::CASE_INSENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }

        if (!empty($ini->properties)) {

            $properties = array_map(function (string $x): string { return "\$$x";}, (array) $ini->properties);

            $this->atomIs('Staticproperty')
                 ->outIs('CLASS')
                 ->fullnspathIs(array_keys($properties))
                 ->savePropertyAs('fullnspath', 'fqn')
                 ->back('first')

                 ->outIs('MEMBER')
                 ->isHash('fullcode', $properties, 'fqn', self::CASE_SENSITIVE)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
