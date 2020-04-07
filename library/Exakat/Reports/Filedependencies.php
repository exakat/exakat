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

namespace Exakat\Reports;

class FileDependencies extends Reports {
    const FILE_EXTENSION = 'dot';
    const FILE_FILENAME  = 'filedependencies';

    public function generate(string $folder, string $name= 'dependencies'): string {
        $res = $this->dump->fetchTable('filesDependencies');
        $res = array_filter($res->toArray(), function (array $x) { return $x['including'] !== $x['included']; });

        $colors = array('include'          => 'green',
                        'staticmethodcall' => 'purple',
                        'staticconstant'   => 'purple',
                        'staticproperty'   => 'purple',
                        'typehint'         => 'purple',
                        'use'              => 'orange',
                        'new'              => 'blue',
                        'clone'            => 'blue',
                        'extends'          => 'red',
                        'implements'       => 'red',
                        'functioncall'     => 'blue',
                        'constant'         => 'blue',
                        );

        $nodes = array();
        $cnodes = 0;

        $list = array();
        foreach($res as $row) {
            if (isset($nodes[$row['including']])) {
                $row['including'] = $nodes[$row['including']];
            } else {
                $nodes[$row['including']] = ++$cnodes;
                $row['including'] = $cnodes;
            }

            if (isset($nodes[$row['included']])) {
                $row['included'] = $nodes[$row['included']];
            } else {
                $nodes[$row['included']] = ++$cnodes;
                $row['included'] = $cnodes;
            }

            $key = $row['including'] . $row['included'] . $row['type'];

            if (isset($list[$key])) {
                ++$list[$key]['count'];
            } else {
                $row['count'] = 1;
                $row['color'] = $colors[$row['type']];
                $list[$key] = $row;
            }

            $this->count();
        }

        $list = array_map(function ($row) {
            return "\"$row[including]\" -> \"$row[included]\" [label=\"$row[type] ($row[count])\" color=\"$row[color]\" ];";
        }, $list);
        $dot = implode(PHP_EOL, $list);

        $nodes = array_map(function ($key, $value) {
            return "$value [label=\"$key\" shape=\"tab\" style=\"filled\" fillcolor=\"chartreuse3\"];";
        },
                           array_keys($nodes),
                           array_values($nodes));
        $nodes = implode(PHP_EOL, $nodes);

        $version = \Exakat\Exakat::VERSION . ' (' . \Exakat\Exakat::BUILD . ')';
        $date = date('r');

        $dot = "digraph graphname {
/* This file was generated by Exakat $version.
   date : $date
   http://www.exakat.io/  
*/

    $nodes
    $dot
     }";

        file_put_contents("{$folder}/{$name}." . self::FILE_EXTENSION, $dot);
        return '';
    }
}

?>