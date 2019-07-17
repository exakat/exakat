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
namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class NoChoice extends Analyzer {
    public function analyze() {
        // $a == 2 ? doThis() : doThis();
        $this->atomIs('Ternary')
             ->outIs('THEN')
             ->savePropertyAs('fullcode', 'cdt')
             ->inIs('THEN')
             ->outIs('ELSE')
             ->samePropertyAs('fullcode', 'cdt', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // $a == 2 ?: doThis();
        $this->atomIs('Ternary')
             ->filter(
                $this->side()
                     ->outIs('THEN')
                     ->atomIs('Void')
                     ->count()
                     ->isEqual(1)
              )
             ->outIs('CONDITION')
             ->atomIs(self::$CONTAINERS)
             ->savePropertyAs('fullcode', 'cdt')
             ->inIs('CONDITION')
             ->outIs('ELSE')
             ->atomIs(self::$CONTAINERS)
             ->samePropertyAs('fullcode', 'cdt', self::CASE_SENSITIVE)
             ->back('first');
        $this->prepareQuery();

        // if ($a == 2) Then doThis(); else doThis();
        $this->atomIs('Ifthen')
             ->outIs('THEN')
             ->atomIs('Sequence')
             ->raw('sideEffect{ sthen = []; it.get().vertices(OUT, "EXPRESSION").sort{it.value("rank")}.each{ sthen.add(it.value("fullcode"));} }')
             ->inIs('THEN')
             ->outIs('ELSE')
             ->atomIs('Sequence')
             ->raw('sideEffect{ selse = []; it.get().vertices(OUT, "EXPRESSION").sort{it.value("rank")}.each{ selse.add(it.value("fullcode"));} }')
             ->filter('sthen.join(";") == selse.join(";")')
             ->back('first');
        $this->prepareQuery();

    }
}

?>
