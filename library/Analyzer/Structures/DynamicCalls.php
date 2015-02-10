<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Structures;

use Analyzer;

class DynamicCalls extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        // dynamic constants
        $this->atomFunctionIs('constant');
        $this->prepareQuery();

        // $$v variable variables
        $this->atomIs('Variable')
             ->outIs('NAME')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // dynamic functioncall
        $this->atomIs('Functioncall')
             ->outIs('NAME')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();

        // dynamic new
        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();

        // property
        // $$o->p
        $this->atomIs('Property')
             ->outIs('OBJECT')
             ->atomIsNot(array('Variable', 'Methodcall', 'Property', 'Staticproperty', 'Staticmethodcall', 'Array'))
             ->back('first');
        $this->prepareQuery();

        // $o->{$p}
        $this->atomIs('Property')
             ->outIs('PROPERTY')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // method
        // $$o->m()
        $this->atomIs('Methodcall')
             ->outIs('OBJECT')
             ->atomIsNot(array('Variable', 'Methodcall', 'Property', 'Staticproperty', 'Staticmethodcall', 'Array'))
             ->back('first');
        $this->prepareQuery();

        // $o->{$m}()
        $this->atomIs('Methodcall')
             ->outIs('PROPERTY')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // static constants
        // use constant() or reflexion
        
        
        // static property
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->back('first');
        $this->prepareQuery();

        // $o->{$p}
        $this->atomIs('Staticproperty')
             ->outIs('PROPERTY')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->back('first');
        $this->prepareQuery();

        // static methods
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->back('first');
        $this->prepareQuery();

        // $o::{$p}()
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

// class_alias
// call_user_func_array and co
// classes in names
// support reflection
    }
}

?>