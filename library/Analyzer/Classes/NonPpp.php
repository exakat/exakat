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


namespace Analyzer\Classes;

use Analyzer;

class NonPpp extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("MethodDefinition");
    }
    
    public function analyze() {
        $this->atomIs("Identifier")
             ->analyzerIs("Analyzer\\Classes\\MethodDefinition")
             ->inIs('NAME')
             ->hasNoOut(array('PUBLIC', 'PROTECTED', 'PRIVATE'));
        $this->prepareQuery();

        $this->atomIs("Ppp")
             ->hasNoOut(array('PUBLIC', 'PROTECTED', 'PRIVATE'))
             ->hasOut('DEFINE')
             ->inIs('ELEMENT')
             ->inIs('BLOCK')
             ->atomIs(array('Class', 'Trait'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>
