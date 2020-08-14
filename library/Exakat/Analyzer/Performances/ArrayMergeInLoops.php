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


namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class ArrayMergeInLoops extends Analyzer {
    public function analyze(): void {
        $functions = array('\\array_merge',
                           '\\array_merge_recursive',
//                           '\\file_put_contents',
                           );

        // foreach($a as $b) { $c = array_merge($c, $b); };
        $this->atomFunctionIs($functions)
             ->hasLoop()
             ->atomInsideNoDefinition(self::CONTAINERS)
             ->savePropertyAs('fullcode', 'var')
             ->back('first')
             ->inIs('RIGHT')
             ->atomIs('Assignation')
             ->codeIs('=')
             ->outIs('LEFT')
             ->atomIs(self::CONTAINERS)
             ->samePropertyAs('fullcode', 'var')
             ->goToLoop();
        $this->prepareQuery();

        // with one level of functioncall : foreach($a as $b) { foo($a); }; function foo(c) { return array_merge($c); }
        $this->atomFunctionIs($functions)
             ->hasNoLoop()
             ->hasIn('RETURN')
             ->goToFunction()
             ->outIs('DEFINITION')
             ->goToLoop();
        $this->prepareQuery();

        // with one level of functioncall : array_map($array, 'foo'); function foo(c) { array_merge($c); }
        $this->atomFunctionIs($functions)
             ->hasNoLoop()
             ->goToFunction()
             ->outIs('DEFINITION')
             ->atomIs(self::STRINGS_ALL, self::WITH_CONSTANTS)
             ->inIs('ARGUMENT')
             ->functioncallIs('\\array_map');
        $this->prepareQuery();
    }
}

?>
