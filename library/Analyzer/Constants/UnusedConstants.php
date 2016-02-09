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


namespace Analyzer\Constants;

use Analyzer;

class UnusedConstants extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Constants/ConstantUsage');
    }
    
    public function analyze() {
        $thirdArgIsTrue  = 'it.out("ARGUMENT").filter{it.rank == 2}.any() && it.out("ARGUMENT").filter{it.rank == 2}.filter{it.code.toLowerCase() == "true"}.any()';
        $thirdArgIsFalse = 'it.out("ARGUMENT").filter{it.rank == 2}.any() == false || it.out("ARGUMENT").filter{it.rank == 2}.filter{it.code.toLowerCase() == "false"}.any()';

        // Const from a define (case insensitive)
        $this->atomFunctionIs('\define')
             ->outIs('ARGUMENTS')
             ->filter($thirdArgIsFalse)
             ->outIs('ARGUMENT')
             ->hasRank(0)
             ->atomIs('String')
             ->filter('name = it.noDelimiter; g.idx("analyzers")[["analyzer":"Analyzer\\\\Constants\\\\ConstantUsage"]].out("ANALYZED").filter{it.code == name}.any() == false ');
        $this->prepareQuery();

        // Const from a define (case sensitive)
        $this->atomFunctionIs('\define')
             ->outIs('ARGUMENTS')
             ->filter($thirdArgIsTrue)
             ->outIs('ARGUMENT')
             ->hasRank(0)
             ->atomIs('String')
             ->filter('name = it.noDelimiter; g.idx("analyzers")[["analyzer":"Analyzer\\\\Constants\\\\ConstantUsage"]].out("ANALYZED").filter{it.code.toLowerCase() == name.toLowerCase()}.any() == false ');
        $this->prepareQuery();

        // Const from a const
        $this->atomIs('Const')
             ->hasNoClass()
             ->outIs('CONST')
             ->outIs('LEFT')
             ->raw('filter{ name = it.code.toLowerCase(); g.idx("analyzers")[["analyzer":"Analyzer\\\\Constants\\\\ConstantUsage"]].out("ANALYZED").filter{it.code.toLowerCase() == name}.any() == false }');
        $this->prepareQuery();
      }
}

?>
