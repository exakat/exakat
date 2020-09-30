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

namespace Exakat\Analyzer\Complete;

use Exakat\Analyzer\Analyzer;

class PhpExtStubPropertyMethod extends Analyzer {
    /* PHP version restrictions
    protected $phpVersion = '7.4-';
    */

    public function dependsOn(): array {
        return array('Complete/CreateDefaultValues',
                    );
    }

    public function analyze(): void {
        $rulesets = exakat('rulesets');
        $exts = $rulesets->listAllAnalyzer('Extensions');

        $properties = array();
        $methods    = array();
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext);
            if (!file_exists($this->config->dir_root . '/data/' . $inifile . '.ini')) {
                continue;
            }
            $ini = parse_ini_file($this->config->dir_root . '/data/' . $inifile . '.ini');

            if (!empty($ini['methods'][0])) {
                foreach($ini['methods'] as $fullMethod) {
                    list($class, $method) = explode('::', $fullMethod, 2);
                    array_collect_by($methods, mb_strtolower($method), makeFullnspath($class));
                }
            }

            if (!empty($ini['properties'][0])) {
                foreach($ini['properties'] as $fullProperty) {
                    list($class, $property) = explode('::', $fullProperty, 2);
                    array_collect_by($properties, ltrim($property, '$'), makeFullnspath($class));
                }
            }
        }

        // $mysqli->$p with typehints
        $this->atomIs('Member')
             ->isNot('isExt', true)
             ->outIs('MEMBER')
             ->fullcodeIs(array_keys($properties))
             ->savePropertyAs('fullcode', 'property')
             ->back('first')

             ->outIs('OBJECT')
             ->atomIs('Variableobject')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('TYPEHINT')
             ->atomIs(self::STATIC_NAMES)
             ->isHash('fullnspath', $properties, 'property')
             ->back('first')
             ->property('isExt', true);
        $this->prepareQuery();

        // $mysqli->$p with local new
        $this->atomIs('Member')
             ->isNot('isExt', true)
             ->outIs('MEMBER')
             ->fullcodeIs(array_keys($properties))
             ->savePropertyAs('fullcode', 'property')
             ->back('first')

             ->outIs('OBJECT')
             ->atomIs('Variableobject')
             ->filter(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('DEFAULT')
                     ->atomIs('New')
                     ->outIs('NEW')
                     ->isHash('fullnspath', $properties, 'property')
             )
             ->back('first')
             ->property('isExt', true);
        $this->prepareQuery();

        // $mysqli->m() with typehints
        $this->atomIs('Methodcall')
             ->isNot('isExt', true)
             ->outIs('METHOD')
             ->outIs('NAME')
             ->fullcodeIs(array_keys($methods), self::CASE_INSENSITIVE)
             ->savePropertyAs('fullcode', 'method')
             ->raw('sideEffect{ method = method.toLowerCase(); }')
             ->back('first')

             ->outIs('OBJECT')
             ->atomIs('Variableobject')
             ->inIs('DEFINITION')
             ->inIs('NAME')
             ->outIs('TYPEHINT')
             ->atomIs(self::STATIC_NAMES)
             ->isHash('fullnspath', $methods, 'method')
             ->back('first')
             ->property('isExt', true);
        $this->prepareQuery();

        // $mysqli->m() with local new
        $this->atomIs('Methodcall')
             ->isNot('isExt', true)
             ->outIs('METHOD')
             ->outIs('NAME')
             ->fullcodeIs(array_keys($methods), self::CASE_INSENSITIVE)
             ->savePropertyAs('fullcode', 'method')
             ->raw('sideEffect{ method = method.toLowerCase(); }')
             ->back('first')

             ->outIs('OBJECT')
             ->atomIs('Variableobject')
             ->filter(
                $this->side()
                     ->inIs('DEFINITION')
                     ->outIs('DEFAULT')
                     ->atomIs('New')
                     ->outIs('NEW')
                     ->isHash('fullnspath', $methods, 'method')
             )
             ->back('first')
             ->property('isExt', true);
        $this->prepareQuery();
    }
}

?>
