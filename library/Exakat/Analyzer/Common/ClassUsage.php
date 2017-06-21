<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class ClassUsage extends Analyzer {
    protected $classes = array();
    
    public function setClasses($classes) {
        $this->classes = $classes;
    }
    
    public function analyze() {
        $classes =  $this->makeFullNsPath($this->classes);

        $this->atomIs('Newcall')
             ->raw('not(where( __.out("NAME").hasLabel("Array", "Variable", "Member", "Staticproperty", "Methodcall", "Staticmethodcall") ) )')
             ->tokenIs(self::$FUNCTIONS_TOKENS)
             ->fullnspathIs($classes);
        $this->prepareQuery();

        $this->atomIs('New')
             ->outIs('NEW')
             ->atomIs(array('Identifier', 'Nsname'))
             ->fullnspathIs($classes);
        $this->prepareQuery();
        
        $this->atomIs(array('Staticmethodcall', 'Staticproperty', 'Staticconstant'))
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot('Array')
             ->fullnspathIs($classes);
        $this->prepareQuery();

        $this->atomIs('Catch')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspathIs($classes);
        $this->prepareQuery();

        $this->atomIs(array('Nsname', 'Identifier'))
             ->hasIn('TYPEHINT')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspathIs($classes);
        $this->prepareQuery();

        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot(array('Array', 'Null', 'Boolean'))
             ->fullnspathIs($classes);
        $this->prepareQuery();

        $this->atomIs('Class')
             ->outIs(array('EXTENDS', 'IMPLEMENTS'))
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspathIs($classes);
        $this->prepareQuery();

// Check that... Const/function and aliases
        // This points on the use, not the name.
        $this->atomIs('Use')
             ->outIs('USE')
//             ->outIsIE('NAME')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR', 'T_AS'))
             ->fullnspathIs($classes);
        $this->prepareQuery();

        $this->atomFunctionIs('\\class_alias')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->noDelimiterIs($classes);
        $this->prepareQuery();
    }
}

?>
