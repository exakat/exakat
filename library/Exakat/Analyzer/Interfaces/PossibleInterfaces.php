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

namespace Exakat\Analyzer\Interfaces;

use Exakat\Analyzer\Analyzer;

class PossibleInterfaces extends Analyzer {
    /* PHP version restrictions
    protected $phpVersion = '7.4-';
    */

    /* List dependencies 
    public function dependsOn() {
        return array('Category/Analyzer',
                     '',
                    );
    }
    */
    // remove abstract, final,
    // remove protected, private ??
    // remove extended ... ??
    // remove already interfaced (tough luck)
    // include traits?? Nope, for the first take

    public function analyze() {
        $this->atomIs('Class')
             ->collectMethods('methods')
             ->raw('filter{ methods.size() > 1; }')
             ->raw('map{ methods.add(it.get().value("fullnspath")); methods; }');
        $res = $this->rawQuery();

        $interfaces = $res->toArray();
        // at least one method
        $interfaces = array_filter($interfaces, function ($x) { return count($x) > 1; });
        $interfaces = array_unique($interfaces, SORT_REGULAR);
        $interfaces = array_values($interfaces);

        $list = $interfaces;
        $all = array();
        foreach($interfaces as $id => $one) {
            $stats[$id] = 0;
            $current = array_pop($one);

            foreach($list as $interface) {
                $interface_name = array_pop($interface);

                if (!empty($diff = array_intersect($interface, $one))) {
                    ++$stats[$id];

                    if (count($diff) >= 2) {
                        sort($diff);
                        $all[] = implode('-', $diff);
                    }
                }
            }

            // This cuts the tests by 2
            array_shift($list);

        }
        $counts = array_count_values($all);

        // at least 2 methods in common
        $counts = array_filter($counts, function ($x) { return $x >= 2;});

        if (empty($counts)) {
            return ;
        }

        foreach(array_keys($counts) as $count) {
            $arg = explode('-', (string) $count);
            $arg = array_map('intval', $arg);
            $this->atomIs('Class')
                 ->analyzerIsNot('self')
                 ->collectMethods('methods')
                 ->raw('filter{ methods.size() > 1; }')
                 ->raw('filter{ methods.intersect(***) == ***; }', $arg, $arg);
            $this->prepareQuery();
        }
    }
}

?>
