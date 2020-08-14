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


namespace Exakat\Query\DSL;


class SaveOutAs extends DSL {
    public function run(): Command {
        switch(func_num_args()) {
            case 3:
                list($name, $out, $sort) = func_get_args();
                break 1;

            case 2:
                list($name, $out) = func_get_args();
                $sort = 'rank';
                break 1;

            case 1:
                list($name) = func_get_args();
                $sort = 'rank';
                $out = 'ARGUMENT';
                break 1;

            default:
                assert(false, 'Wrong number of argument for ' . __METHOD__ . '. 1 to 3 are expected, ' . func_num_args() . ' provided');
        }

        // Calculate the arglist, normalized it, then put it in a variable
        // This needs to be in Arguments, (both Functioncall or Function)
        if (empty($sort)) {
            $sortStep = '';
        } else {
            $sortStep = ".sort{it.value(\"$sort\")}";
        }

        $check = $this->dslfactory->factory('initVariable');
        $return = $check->run($name);

        $gremlin = <<<GREMLIN
sideEffect{ 
    s = [];
    it.get().vertices(OUT, "$out")$sortStep.each{ 
        s.push(it.value('code'));
    };
    $name = s.join(', ');
    true;
}

GREMLIN;
        return $return->add(new Command($gremlin));
    }
}
?>
