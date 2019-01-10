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

use stdClass;

class Filedependencieshtml extends Reports {
    const FILE_EXTENSION = 'html';
    const FILE_FILENAME  = 'dependencies';
    
    private $finalName   = '';
    private $tmpName     = '';

    public function generate($folder, $name= 'dependencies') {
        $this->finalName = $folder.'/'.$name;
        $this->tmpName = $folder.'/.'.$name;

        copyDir($this->config->dir_root.'/media/dependencies', $this->tmpName );

        $res = $this->sqlite->query('SELECT * FROM filesDependencies');

        $json = new stdClass();
        $json->edges = array();
        $json->nodes = array();
        $in = array();
        $out = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (isset($json->nodes[$row['including']])){
                $source = $json->nodes[$row['including']];
                ++$in[$source];
            } else {
                $source = count($json->nodes);
                $json->nodes[$row['including']] = $source;
                $in[$source] = 0;
                $out[$source] = 0;
            }

            if (isset($json->nodes[$row['included']])){
                $destination = $json->nodes[$row['included']];
                ++$out[$destination];
            } else {
                $destination = count($json->nodes);
                $json->nodes[$row['included']] = $destination;
                $in[$destination] = 0;
                $out[$destination] = 0;
            }

            $R = new stdClass();
            $R->source = $source;
            $R->target = $destination;
            $R->caption = $row['type'];
            $json->edges[] = $R;
        }

        $json->nodes = array_flip($json->nodes);
        foreach($in as $id => $i) {
            $json->nodes[$id] = (object) array('id'       => $id,
                                               'caption'  => $json->nodes[$id],
                                               'incoming' => $i,
                                               'outgoing' => $out[$id]);
        }

        file_put_contents($this->tmpName.'/fidep.json', json_encode($json));

        // Finalisation
        if ($this->finalName !== '/') {
            rmdirRecursive($this->finalName);
        }

        if (file_exists($this->finalName)) {
            display($this->finalName." folder was not cleaned. Please, remove it before producing the report. Aborting report\n");
            return;
        }

        rename($this->tmpName, $this->finalName);
    }
}

?>