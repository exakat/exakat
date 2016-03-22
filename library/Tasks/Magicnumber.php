<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Tasks;

class Magicnumber extends Tasks {
    public function run(\Config $config) {
        $project = $config->project;
        if ($project == 'default') {
            die("Magicnumber needs a -p <project>\nAborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/')) {
            die("No such project as $project.\nAborting\n");
        }

        $sqliteFile = $config->projects_root.'/projects/'.$config->project.'/magicnumber.sqlite';
        if (file_exists($sqliteFile)) {
            unlink($sqliteFile);
        }
        $sqlite = new \SQLite3($sqliteFile);

        $types = array('Integer', 'String', 'Float');

        foreach( $types as $type) {
            $query = <<<SQL
m = [:];
g.idx('atoms')[['atom':'$type']].groupCount(m){it.code}.iterate();
m.findAll()
SQL;
            $res = $this->gremlin->query($query);
            $res = $res->results;

            $sqlite->exec('CREATE TABLE '.$type.' (id INTEGER PRIMARY KEY, value STRING, count INTEGER)');
            $stmt = $sqlite->prepare('INSERT INTO '.$type.' (value, count) VALUES(:value, :count)');

            $total = 0;
            foreach($res as $v) {
                preg_match('/^(.*)=(\d+)/is', $v, $r);
                $value = $r[1];
                $count = $r[2];
                $stmt->bindValue(':value', $value, SQLITE3_TEXT);
                $stmt->bindValue(':count', $count, SQLITE3_INTEGER);
                $stmt->execute();
                ++$total;
            }
            display( "$type : $total\n");
        }

        // export big arrays (more than 10)
        $res = $this->gremlin->query("g.V.has('token', 'T_ARRAY').filter{ it.out('ARGUMENTS').out('ARGUMENT').count() > 10}.fullcode");
        $res = $res->results;
        
        $outputFile = fopen($config->projects_root.'/projects/'.$config->project.'/bigArrays.txt', 'w+');
        foreach($res as $v) {
            fwrite($outputFile, $v."\n");
        }
        fclose($outputFile);
    }
}

?>
