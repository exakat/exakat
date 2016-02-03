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


namespace Analyzer\Variables;

use Analyzer;

class Variablenames extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Variables/Blind');
    }
    
    public function analyze() {
        // $x
        $this->atomIs('Variable')
             ->hasNoParent('Functioncall', array('NAME'))
             ->hasNoParent('Class', array('LEFT', 'DEFINE', 'ELEMENT', 'BLOCK'))
             ->hasNoParent('Class', array('DEFINE', 'ELEMENT', 'BLOCK'))
             ->hasNoParent('Staticproperty', 'PROPERTY')
             ->hasNoParent('Staticproperty', array('VARIABLE', 'PROPERTY'))
             ->hasNoParent('Staticproperty', array('VARIABLE', 'VARIABLE', 'PROPERTY'))
             ->analyzerIsNot('Variables/Blind');
        $this->prepareQuery();

        // $object->$x()
        $this->atomIs('Functioncall')
             ->tokenIs('T_VARIABLE');
        $this->prepareQuery();

        // $object->$x or $object->{$x}
        $this->atomIs('Property')
             ->outIs('PROPERTY')
             ->tokenIs('T_VARIABLE');
        $this->prepareQuery();

        // ${'x'}
        $this->atomIs('Variable')
             ->hasNoParent('Class', array('DEFINE', 'ELEMENT', 'BLOCK'))
             ->hasNoParent('Class', array('LEFT', 'DEFINE', 'ELEMENT', 'BLOCK'))
             ->hasNoParent('Staticproperty', 'PROPERTY')
             ->hasNoParent('Staticproperty', array('VARIABLE', 'PROPERTY'))
             ->analyzerIsNot('Variables/Blind')
             ->tokenIs('T_DOLLAR')
             ->outIs('NAME')
             ->tokenIs('T_STRING')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
