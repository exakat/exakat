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

class ConstantDefinition extends Analyzer {
    protected $constants = array();
    
    public function analyze() {
        $fullnspath = makeFullNsPath($this->constants, true);
        
        $this->atomIs('Const')
             ->hasNoClassInterface()
             ->outIs('CONST')
             ->outIs('NAME')
             ->fullnspathIs($fullnspath)
             ->inIs('NAME');
        $this->prepareQuery();

        $this->atomIs('Defineconstant')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->fullnspathIs($fullnspath);
        $this->prepareQuery();
    }
}

?>
