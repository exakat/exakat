<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Classes;

use Analyzer;

class AvoidUsing extends Analyzer\Analyzer {
    public function analyze() {
        $config = \Config::factory();
        $classes = $config->Classes_AvoidUsing;
        
        if (empty($classes)) {
            return null;
        }
        $classes = $this->makeFullNsPath($classes);

        // class may be used in a class
        $this->atomIs('Class')
             ->fullnspathIs($classes)
             ->back('first');
        $this->prepareQuery();
        
        // class may be used in a new
        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspathIs($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Staticmethodcall
        $this->atomIs(array('Staticmethodcall', 'Staticproperty', 'Staticconstant', 'Instanceof'))
             ->outIs('CLASS')
             ->fullnspathIs($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a typehint
        $this->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->fullnspathIs($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in an extension
        $this->atomIs('Class')
             ->outIs(array('EXTENDS', 'IMPLEMENTS'))
             ->fullnspathIs($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in an use
        $this->atomIs('Use')
             ->outIs('USE')
             ->fullnspathIs($classes)
             ->back('first');
        $this->prepareQuery();

        $this->atomFunctionIs('\\class_alias')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->noDelimiterIs($classes);
        $this->prepareQuery();

        // mentions in strings
        $this->atomIs('String')
             ->noDelimiterIs($config->Classes_AvoidUsing);
        $this->prepareQuery();

    }
}

?>
