<?php
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


namespace Exakat\Analyzer\Constants;

use Exakat\Analyzer\Analyzer;

class UnusedConstants extends Analyzer {
    /*
    public function dependsOn() {
        return array('Constants/ConstantUsage',
                    );
    }
    */
    
    public function analyze() {
        /*
        $queryConstants = <<<GREMLIN
g.V().hasLabel("Analysis")
     .has("analyzer", "Constants/ConstantUsage")
     .out("ANALYZED")
     .map{ 
        if (it.get().label() == "String") {
          it.get().value("noDelimiter");
        } else {
          it.get().value("fullcode");
        }
     }
     .unique()
GREMLIN;
        $constants = $this->query($queryConstants)
                          ->toArray();

        // Const from a define (case insensitive)
        $this->atomIs('Defineconstant')
             ->noChildWithRank('ARGUMENT', 2) // default, case sensitive
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->noDelimiterIsNot($constants, self::CASE_SENSITIVE);
        $this->prepareQuery();
        
        $this->atomIs('Defineconstant')
             ->outWithRank('ARGUMENT', 2) // explicit, case sensitive
             ->is('boolean', false)
             ->inIs('ARGUMENT')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->noDelimiterIsNot($constants, self::CASE_SENSITIVE);
        $this->prepareQuery();
        
        // Const from a define (case sensitive)
        $this->atomFunctionIs('\define')
             ->outWithRank('ARGUMENT', 2) // explicit, case sensitive
             ->is('boolean', true)
             ->inIs('ARGUMENT')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->noDelimiterIsNot($constants);
        $this->prepareQuery();
        */

        $this->atomIs('Defineconstant')
             ->hasNoOut('DEFINITION')
             ->outWithRank('ARGUMENT', 0);
        $this->prepareQuery();

        // Const from a const
        $this->atomIs('Const')
             ->hasNoClassInterface()
             ->outIs('CONST')
             ->hasNoOut('DEFINITION');
        $this->prepareQuery();
    }
}

?>
