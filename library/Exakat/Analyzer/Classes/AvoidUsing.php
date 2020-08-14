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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class AvoidUsing extends Analyzer {
    protected $forbiddenClasses = array('AvoidThisClass');

    public function analyze(): void {
        $classes = $this->forbiddenClasses;

        if (empty($classes)) {
            return ;
        }
        $classesPath = makeFullNsPath($classes);

        // class may be used in a class
        $this->atomIs(self::CLASSES_ALL)
             ->fullnspathIs($classesPath);
        $this->prepareQuery();

        // class may be used in a new
        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIs(self::STATICCALL_TOKEN)
             ->fullnspathIs($classesPath)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Staticmethodcall, instanceof, catch
        $this->atomIs(array('Staticmethodcall', 'Staticproperty', 'Staticconstant', 'Instanceof', 'Catch'))
             ->outIs('CLASS')
             ->fullnspathIs($classesPath)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a typehint
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->fullnspathIs($classesPath)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a return typehint
        $this->atomIs(self::FUNCTIONS_ALL)
             ->outIs('RETURNTYPE')
             ->fullnspathIs($classesPath)
             ->back('first');
        $this->prepareQuery();

        // class may be used in an extension
        $this->atomIs(self::CLASSES_ALL)
             ->outIs(array('EXTENDS', 'IMPLEMENTS'))
             ->fullnspathIs($classesPath)
             ->back('first');
        $this->prepareQuery();

        // class may be used in an use
        $this->atomIs('Usenamespace')
             ->outIs('USE')
             ->fullnspathIs($classesPath)
             ->back('first');
        $this->prepareQuery();

        // class_alias is covered by string test just below
        // mentions in strings
        $this->atomIs(self::STRINGS_LITERALS)
             ->regexIs('noDelimiter', '(?i)^' . addslashes(implode('|', $classes)) . '\\$');
        $this->prepareQuery();
    }
}

?>
