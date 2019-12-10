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

namespace Exakat\Reports;

use Exakat\Reports\Helpers\Dot;

class Topology extends Reports {
    const FILE_EXTENSION = 'dot';
    const FILE_FILENAME  = 'exakat.topology';

    public function _generate($analyzerList) {
        switch($this->config->program) {
            case 'Dump/Typehintorder' :
                $query = 'SELECT argument AS origin, returned AS destination FROM typehintOrder';
                break;

            case 'Dump/NewOrder' :
                $query = 'SELECT calling AS origin, called AS destination FROM newOrder';
                break;

            default :
                display('Call this report with -P Dump/Typehintorder or -P Dump/NewOrder');
                return '';
        }

        $res = $this->sqlite->query($query);
        $nodes = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $nodes[] = $row;
        }

        $names = array();
        foreach($nodes as $id => list('origin' => $origin, 'destination' => $destination)) {
            if (strpos($origin, '@') !== false ||
                strpos($destination, '@') !== false
                ) {
                unset($nodes[$id]);
                continue;
            }

            if (!isset($names[$origin] )) {
                $names[] = $origin;
            }

            if (!isset($names[$destination] )) {
                $names[] = $destination;
            }
        }

        $names = array_flip($names);

        $dot = new Dot();

        $dot->setOptions('nodes', 'shape', 'square');
        $dot->setOptions('nodes', 'style', 'filled');
        $dot->setOptions('node', 'colorscheme', 'paired12');

        $names2 = array();
        $atoms = array_map(function ($id, $name) use ($dot, &$names2) {
            $d = explode('\\', $name);
            $name2 = array_pop($d);
            $color = 1 + $id % 11;
            $names2[$name] = $dot->addNode($name2, array('fillcolor' => $color));
        },
                            array_values($names),
                            array_keys($names)
                            );
        $atoms = implode('', $atoms);
        $names = $names2;

        $links = array();
        foreach($nodes as list('origin' => $origin, 'destination' => $destination)) {
            $dot->addLink($names[$destination], $names[$origin]);
        }

        $links = array_unique($links);
        $links = implode('', $links);

        return (string) $dot;
    }
}

?>