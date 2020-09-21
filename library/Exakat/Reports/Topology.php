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

use Exakat\Reports\Helpers\Dot;

class Topology extends Reports {
    const FILE_EXTENSION = 'dot';
    const FILE_FILENAME  = 'exakat.topology';

    public function _generate(array $analyzerList): string {
        $program = strtolower($this->config->program);

        switch($program) {
            case 'dump/typehintorder' :
                $res = $this->dump->fetchTable('typehintOrder', array('origin'        => 'argument',
                                                                      'originId'      => 'argument',
                                                                      'destination'   => 'returned',
                                                                      'destinationId' => 'returned',
                                                                      ));
                break;

            case 'dump/callorder' :
                $res = $this->dump->fetchTable('callOrder', array('origin'        => 'calledName',
                                                                  'originId'      => 'called',
                                                                  'destination'   => 'callingName',
                                                                  'destinationId' => 'calling',
                                                                  ));
                break;

            default :
            case 'dump/neworder' :
                $res = $this->dump->fetchTable('newOrder', array('origin'        => 'calling',
                                                                 'originId'      => 'calling',
                                                                 'destination'   => 'calling',
                                                                 'destinationId' => 'calling',
                                                                 ));
                break;
        }

        $names = array();
        foreach($res->toArray() as $id => list('origin'      => $origin,      'originId'      => $originId,
                                               'destination' => $destination, 'destinationId' => $destinationId,
                                               )) {
            if (strpos($origin, '@') !== false ||
                strpos($destination, '@') !== false
                ) {
                unset($nodes[$id]);
                continue;
            }

            if (!isset($names[$origin] )) {
                $names[$originId] = $origin;
            }

            if (!isset($names[$destination] )) {
                $names[$destinationId] = $destination;
            }
        }

        $names = array_flip($names);

        $dot = new Dot();

        $dot->setOptions('nodes', 'shape', 'square');
        $dot->setOptions('nodes', 'style', 'filled');
        $dot->setOptions('node', 'colorscheme', 'paired12');

        $names2 = array();
        $color = 0;
        $atoms = array_map(function ($id, string $name) use ($dot, &$names2, &$color) : void {
            $d = explode('\\', $name);
            $name2 = array_pop($d);
            $color = 1 + ($color++) % 11;
            $names2[$name] = $dot->addNode($name2, array('fillcolor' => $color));
        },
                            array_values($names),
                            array_keys($names)
                            );
        $atoms = implode('', $atoms);
        $names = $names2;

        $links = array();
        foreach($res->toArray() as list('origin' => $origin, 'destination' => $destination)) {
            $dot->addLink($names[$destination], $names[$origin]);
        }

        $links = array_unique($links);
        $links = implode('', $links);

        return (string) $dot;
    }
}

?>