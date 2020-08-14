<?php declare(strict_types = 1);
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
namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class LogicalMistakes extends Analyzer {
    public function analyze(): void {
        // Note : support for parenthesis is added.

        //if ( $a != 1 || $a != 2)
        $this->atomIs('Logical')
             ->codeIs(array('||', 'or'))
             ->outIs('LEFT')
             ->outIsIE('CODE')
             ->atomIs('Comparison')
             ->codeIs(array('!=', '!=='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::CONTAINERS)
             ->savePropertyAs('fullcode', 'var')
             ->inIs(array('LEFT', 'RIGHT'))
             ->inIsIE('CODE')
             ->inIs('LEFT')

             ->outIs('RIGHT')
             ->outIsIE('CODE')
             ->atomIs('Comparison')
             ->codeIs(array('!=', '!=='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::CONTAINERS)
             ->samePropertyAs('fullcode', 'var')
             ->back('first');
        $this->prepareQuery();

        //if ( $a == 1 || $a != 2)
        $this->atomIs('Logical')
             ->codeIs(array('||', 'or'))
             ->outIs('LEFT')
             ->outIsIE('CODE')
             ->atomIs('Comparison')
             ->codeIs(array('==', '==='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::CONTAINERS)
             ->savePropertyAs('fullcode', 'var')
             ->inIs(array('LEFT', 'RIGHT'))
             ->inIsIE('CODE')
             ->inIs('LEFT')

             ->outIs('RIGHT')
             ->outIsIE('CODE')
             ->atomIs('Comparison')
             ->codeIs(array('!=', '!=='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::CONTAINERS)
             ->samePropertyAs('fullcode', 'var')
             ->back('first');
        $this->prepareQuery();

        //if ( $a == 1 && $a == 2)
        $this->atomIs('Logical')
             ->codeIs(array('&&', 'and'))
             ->outIs('LEFT')
             ->outIsIE('CODE')
             ->atomIs('Comparison')
             ->codeIs(array('==', '==='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::CONTAINERS)
             ->savePropertyAs('fullcode', 'var')
             ->inIs(array('LEFT', 'RIGHT'))
             ->inIsIE('CODE')
             ->inIs('LEFT')

             ->outIs('RIGHT')
             ->outIsIE('CODE')
             ->atomIs('Comparison')
             ->codeIs(array('==', '==='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::CONTAINERS)
             ->samePropertyAs('fullcode', 'var')
             ->back('first');
        $this->prepareQuery();

        //if ( $a == 1 && $a != 2)
        $this->atomIs('Logical')
             ->codeIs(array('&&', 'and'))
             ->outIs('LEFT')
             ->outIsIE('CODE')
             ->atomIs('Comparison')
             ->codeIs(array('==', '==='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::CONTAINERS)
             ->savePropertyAs('fullcode', 'var')
             ->inIs(array('LEFT', 'RIGHT'))
             ->inIsIE('CODE')
             ->inIs('LEFT')

             ->outIs('RIGHT')
             ->outIsIE('CODE')
             ->atomIs('Comparison')
             ->codeIs(array('!=', '!=='))
             ->outIs(array('LEFT', 'RIGHT'))
             ->atomIs(self::CONTAINERS)
             ->samePropertyAs('fullcode', 'var')
             ->back('first');
        $this->prepareQuery();

        // Extension to this rule :
        // Check for methodcalls, function calls
        // add support for xor (although, it is rare)
        // may be invert == and != ?
    }
}

?>
