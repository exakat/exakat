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
use Exakat\Query\DSL\FollowParAs;

class MismatchedTernary extends Analyzer {
    public function analyze(): void {
        $values = array_merge(self::LITERALS, array('Arrayliteral', 'Concatenation'));

        // $a ? 1 : null
        $this->atomIs('Ternary')
             ->codeIs('?')
             ->outIs('THEN')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIs($values, self::WITH_CONSTANTS)
             ->savePropertyAs('label', 'then')
             ->raw('sideEffect{ if (then == "Concatenation") { then = "String"; } else 
                                if (then == "Heredoc")       { then = "String"; } 
                                 }')
             ->back('first')

             ->outIs('ELSE')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIs($values, self::WITH_CONSTANTS)
             ->savePropertyAs('label', 'notthen')
             ->raw('sideEffect{ if (notthen == "Concatenation") { notthen = "String"; } else 
                                if (notthen == "Heredoc")       { notthen = "String"; } 
                                 }')

             ->raw('filter{ then != notthen; } ')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
