<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ReturnTrueFalse extends Analyzer {
    public function analyze() {

        // If ($a == 2) { return true; } else { return false; }
        // If ($a == 2) { return false; } else { return true; }
        $this->atomIs('Ifthen')

             ->outIs('THEN')
             ->outIs('EXPRESSION')
             ->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Boolean')
             ->savePropertyAs('boolean', 'a')
             ->inIs('RETURN')
             ->inIs('EXPRESSION')
             ->inIs('THEN')

             ->outIs('ELSE')
             ->outIs('EXPRESSION')
             ->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Boolean')
             ->notSamePropertyAs('boolean', 'a')

             ->back('first');
        $this->prepareQuery();

        // If ($a == 2) { $b = true; } else { $b = false; }
        // If ($a == 2) { $b = false; } else { $b = true; }
        $this->atomIs('Ifthen')
             ->outIs('THEN')
             ->outIs('EXPRESSION')
             ->atomIs('Assignation')
             
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'container')
             ->inIs('LEFT')
             
             ->outIs('RIGHT')
             ->isLiteral()
             ->savePropertyAs('boolean', 'valeur')
             ->back('first')

             ->outIs('ELSE')
             ->outIs('EXPRESSION')
             ->atomIs('Assignation')
             
             ->outIs('LEFT')
             ->samePropertyAs('fullcode', 'container')
             ->inIs('LEFT')
             
             ->outIs('RIGHT')
             ->isLiteral()
             ->notSamePropertyAs('boolean', 'valeur')

             ->back('first');
        $this->prepareQuery();

        // $a = ($b == 2) ? true : false;
        // $a = ($b == 2) ? false : true;
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Ternary')

             ->outIs('THEN')
             ->atomIs('Boolean')
             ->savePropertyAs('boolean', 'a')
             ->inIs('THEN')

             ->outIs('ELSE')
             ->atomIs('Boolean')
             ->notSamePropertyAs('boolean', 'a')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
