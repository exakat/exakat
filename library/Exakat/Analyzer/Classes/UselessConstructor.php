<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class UselessConstructor extends Analyzer {
    public function dependsOn() {
        return array('Classes/Constructor');
    }

    public function analyze() {
        $checkConstructor = <<<GREMLIN
not( __.where( __.out("METHOD", "MAGICMETHOD").hasLabel("Method", "Magicmethod")
       .where( __.in("ANALYZED").has("analyzer", "Classes/Constructor")) )
   )
GREMLIN;
        
        // class a (no extends, no implements)
        $this->atomIs('Class')
             ->hasNoOut(array('EXTENDS', 'IMPLEMENTS'))
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->analyzerIs('Classes/Constructor')
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        // class a with extends, one level
        $this->atomIs('Class')
             ->hasOut('EXTENDS')
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->analyzerIs('Classes/Constructor')
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->atomIs('Void')
             ->back('first')
             ->outIs('EXTENDS')
             ->inIs('DEFINITION')
             ->hasNoOut('EXTENDS')
             ->hasNoOut('IMPLEMENTS')
             ->raw($checkConstructor)
             ->back('first');
        $this->prepareQuery();

        // class a with extends, two level
        $this->atomIs('Class')
             ->hasOut('EXTENDS')
             ->outIs('MAGICMETHOD')
             ->atomIs('Magicmethod')
             ->analyzerIs('Classes/Constructor')
             ->outIs('BLOCK')
             ->outIs('EXPRESSION')
             ->atomIs('Void')
             ->back('first')
             ->outIs('EXTENDS')
             ->inIs('DEFINITION')
             ->hasOut('EXTENDS')
             ->raw($checkConstructor)
             ->outIs('EXTENDS')
             ->inIs('DEFINITION')
             ->raw($checkConstructor)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
