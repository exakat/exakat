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


namespace Analyzer\Functions;

use Analyzer;

class EmptyFunction extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Composer/IsComposerNsname');
    }
    
    public function analyze() {
        // standalone function
        $this->atomIs('Function')
             ->hasNoClassTrait()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        // method : then, it should not overwrite a parent's methdo
        $this->atomIs('Function')
             ->hasClassTrait()
             ->outIs('NAME')
             ->savePropertyAs('code', 'name')
             ->inIs('NAME')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->goToClass()
             ->raw('where( __.repeat( out("EXTENDS").in("DEFINITION") ).emit().times(6).out("BLOCK").out("ELEMENT").hasLabel("Function").out("NAME").filter{ it.get().value("code") == name}.count().is(eq(0)) )')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
