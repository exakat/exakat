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

class AddZero extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/PropagateConstants',
                    );
    }

    public function analyze(): void {
        // $x += 0
        $this->atomIs('Assignation')
             ->codeIs(array('+=', '-='), self::TRANSLATE, self::CASE_SENSITIVE)
             ->outIs('RIGHT')
             ->followParAs(FollowParAs::FOLLOW_PARAS_ONLY)
             ->atomIsNot(array('Ternary', 'Coalesce'))
             ->atomIs(array('Integer', 'Null', 'Boolean'), self::WITH_CONSTANTS)
             ->is('intval', 0)
             ->back('first');
        $this->prepareQuery();

        // 0 + ($c = 2)
        $this->atomIs('Addition')
             ->outIs(array('LEFT', 'RIGHT'))
             ->followParAs(FollowParAs::FOLLOW_PARAS_ONLY)
             ->atomIs(array('Integer', 'Null', 'Boolean'), self::WITH_CONSTANTS)
             ->is('intval', 0)
             ->back('first');
        $this->prepareQuery();

        // $a = 0; $c = $a + 2;
        $this->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('RIGHT')
             ->followParAs(FollowParAs::FOLLOW_PARAS_ONLY)
             ->atomIs(array('Integer', 'Null', 'Boolean'), self::WITH_CONSTANTS)
             ->is('intval', 0)
             ->back('first')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->savePropertyAs('fullcode', 'varname')
             ->back('first')
             ->nextSibling()
             ->atomIsNot(array('Function', 'Class', 'Trait', 'Interface', 'Dowhile', 'While', 'Foreach', 'For'))
             ->atomInsideNoDefinition('Addition')
             ->as('results')
             ->outIs(array('LEFT', 'RIGHT'))
             ->samePropertyAs('fullcode', 'varname')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
