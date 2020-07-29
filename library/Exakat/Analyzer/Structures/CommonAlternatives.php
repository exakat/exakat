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

class CommonAlternatives extends Analyzer {
        // some expressions are common between two then / else block
        public function analyze() : void {

        $omit = array('For',
                      'Foreach',
                      'Ifthen',
                      'Dowhile',
                      'While',
                      'Switch',
                      'Match',
                      'Closure',
                      'Arrowfunction',
                      'Continue',
                      'Break',
                      );

        // if ($c) { $a = 1; } else { $a = 1; $b = 2;}
        $this->atomIs('Ifthen')
             ->tokenIs('T_IF')
             ->outIs('THEN')
             ->atomIs('Sequence')
             ->outIs('EXPRESSION')
             ->as('results')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIsNot($omit)
             ->savePropertyAs('fullcode', 'expression')

             ->back('first')
             ->outIs('ELSE')
             ->atomIs('Sequence')
             ->outIs('EXPRESSION')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIsNot($omit)
             ->samePropertyAs('fullcode', 'expression')
             ->back('results');
        $this->prepareQuery();

        // if ($c) { $a = 1; } elseif () { $a = 1; } else { $a = 1; $b = 2;}
        // two levels only
        $this->atomIs('Ifthen')
             ->tokenIs('T_IF')
             ->outIs('THEN')
             ->atomIs('Sequence')
             ->outIs('EXPRESSION')
             ->as('results')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIsNot($omit)
             ->savePropertyAs('fullcode', 'expression')

             ->back('first')
             ->outIs('ELSE')
             ->tokenIs('T_ELSEIF')
             ->as('second')
             ->outIs('THEN')
             ->atomIs('Sequence')
             ->outIs('EXPRESSION')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIsNot($omit)
             ->samePropertyAs('fullcode', 'expression')

             ->back('second')
             ->outIs('ELSE')
             ->atomIs('Sequence')
             ->outIs('EXPRESSION')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIsNot($omit)
             ->samePropertyAs('fullcode', 'expression')
             ->back('results');
        $this->prepareQuery();

        // switch()
        // two levels only
        $this->atomIs(self::SWITCH_ALL)
             ->outIs('CASES')
             ->as('cases')
             ->savePropertyAs('count', 'c')
             ->outWithRank('EXPRESSION', 0)
             ->outIs('CODE')
             ->outIs('EXPRESSION')
             ->followParAs(FollowParAs::FOLLOW_NONE)
             ->atomIsNot($omit)
             ->as('results')
             ->savePropertyAs('fullcode', 'expression')
             ->back('cases')

             ->filter(
                $this->side()
                     ->outIs('EXPRESSION')
                     ->outIs('CODE')
                     ->outIs('EXPRESSION')
                     ->followParAs(FollowParAs::FOLLOW_NONE)
                     ->atomIsNot($omit)
                     ->samePropertyAs('fullcode', 'expression')
                     ->raw('count().filter{ it.get() == c;}')
             )
             ->back('results');
        $this->prepareQuery();
    }
}

?>
