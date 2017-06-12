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
namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class ReturnTrueFalse extends Analyzer {
    public function analyze() {
        // If ($a == 2) { return true; } else { return false; }
        // If ($a == 2) { return false; } else { return true; }
        $this->atomIs('Ifthen')

             ->outIs('THEN')
             ->outIs('ELEMENT')
             ->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Boolean')
             ->codeIs(array('true', 'false'))
             ->savePropertyAs('code', 'a')
             ->inIs('RETURN')
             ->inIs('ELEMENT')
             ->inIs('THEN')

             ->outIs('ELSE')
             ->outIs('ELEMENT')
             ->atomIs('Return')
             ->outIs('RETURN')
             ->atomIs('Boolean')
             ->codeIs(array('true', 'false'))
             ->notSamePropertyAs('code', 'a')

             ->back('first');
        $this->prepareQuery();

        // If ($a == 2) { $b = true; } else { $b = false; }
        // If ($a == 2) { $b = false; } else { $b = true; }
        $this->atomIs('Ifthen')

             ->outIs('THEN')
             ->outIs('ELEMENT')
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->savePropertyAs('fullcode', 'container')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs('Boolean')

             ->codeIs(array('true', 'false'))
             ->savePropertyAs('code', 'a')
             ->inIs('RIGHT')
             ->inIs('ELEMENT')
             ->inIs('THEN')

             ->outIs('ELSE')
             ->outIs('ELEMENT')
             ->atomIs('Assignation')
             ->outIs('LEFT')
             ->samePropertyAs('fullcode', 'container')
             ->inIs('LEFT')
             ->outIs('RIGHT')
             ->atomIs('Boolean')
             ->codeIs(array('true', 'false'))
             ->notSamePropertyAs('code', 'a')

             ->back('first');
        $this->prepareQuery();

        // $a = ($b == 2) ? true : false;
        // $a = ($b == 2) ? false : true;
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Ternary')

             ->outIs('THEN')
             ->atomIs('Boolean')
             ->codeIs(array('true', 'false'))
             ->savePropertyAs('code', 'a')
             ->inIs('THEN')

             ->outIs('ELSE')
             ->atomIs('Boolean')
             ->codeIs(array('true', 'false'))
             ->notSamePropertyAs('code', 'a')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
