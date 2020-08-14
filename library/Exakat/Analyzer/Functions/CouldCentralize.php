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

namespace Exakat\Analyzer\Functions;

use Exakat\Analyzer\Analyzer;

class CouldCentralize extends Analyzer {
    protected $centralizeThreshold = 8;

    // Looking for calls to function with identical literals
    public function analyze(): void {
        $excluded = array('\\\\defined',
                          '\\\\extension_loaded',
                         );
        $excludedList = makeList($excluded);

        foreach(range(0, 3) as $i) {
            $this->atomIs('Functioncall')
                 ->savePropertyAs('fullnspath', 'f')
                 ->fullnspathIsNot($excluded)
                 ->outWithRank('ARGUMENT', $i)
                 ->atomIs(array('String', 'Integer', 'Null', 'Boolean', 'Float'))
                 ->savePropertyAs('fullcode', 'arg')
                 ->raw('groupCount("m").by{f + ", " + arg;}.cap("m").next().findAll {a,b -> b > ' . $this->centralizeThreshold . '}.keySet()');
            $res = $this->rawQuery();

            if (empty($res)) {
                continue;
            }

            $functions = array();
            $args = array();
            foreach($res as $pattern) {
                if (preg_match('/^(\S+), (.*?)$/is', $pattern, $r)) {
                    $functions[] = $r[1];
                    array_collect_by($args, $r[1], $r[2]);
                }
            }

            $this->atomFunctionIs($functions)
                 ->analyzerIsNot('self')
                 ->savePropertyAs('fullnspath', 'name')
                 ->outWithRank('ARGUMENT', $i)
                 ->isHash('fullcode', $args, 'name')
                 ->back('first');
            $this->prepareQuery();
        }

       $this->atomIs('Exit')
            ->outWithRank('ARGUMENT', 0)
            ->atomIs(array('String', 'Integer', 'Null', 'Boolean', 'Float'))
            ->savePropertyAs('fullcode', 'arg')
            ->raw('groupCount("m").by{arg;}.cap("m").next().findAll {a,b -> b > ' . $this->centralizeThreshold . '}.keySet()');
       $res = $this->rawQuery();

       if (!empty($res)) {
           $this->atomIs('Exit')
                ->outWithRank('ARGUMENT', 0)
                ->outIsIE('CODE')
                ->is('fullcode', $res->toArray())
                ->back('first');
           $this->prepareQuery();
       }
    }
}

?>
