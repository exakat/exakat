<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

use Everyman\Neo4j\Client,
	Everyman\Neo4j\Gremlin,
    Everyman\Neo4j\Index\NodeIndex;

class Errors implements Tasks {
    private $client = null;
    
    public function run(\Config $config) {
        $this->client = new Client();

        /*
        $args = $argv;
        if ($id = array_search('-json', $args)) {
            define('FORMAT', 'JSON');
        } elseif ($id = array_search('-text', $args)) {
            define('FORMAT', 'TEXT');
        } elseif ($id = array_search('-table', $args)) {
            define('FORMAT', 'TABLE');
        } else {
            define('FORMAT', 'TEXT');
        }
        */

        $stats = array();
        $stats['files with next'] = $this->queryColumn("g.idx('racines')[['token':'ROOT']].out('INDEXED').out('NEXT').in.loop(1){true}{ it.object.token == 'T_FILENAME'}.code");
        $stats['Function with next'] = $this->queryColumn("g.idx('atoms')[['atom':'_Function']].out('INDEXED').out('NEXT').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Switch'] = $this->queryColumn("g.idx('atoms')[['atom':'_Switch']].out('INDEXED').filter{it.out('NEXT').any()}.transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Addition'] = $this->queryColumn("g.idx('atoms')[['atom':'Addition']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['New'] = $this->queryColumn("g.idx('atoms')[['atom':'_New']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Property'] = $this->queryColumn("g.idx('atoms')[['atom':'Property']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Not'] = $this->queryColumn("g.idx('atoms')[['atom':'Not']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Keyvalue'] = $this->queryColumn("g.idx('atoms')[['atom':'Keyvalue']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Ternary'] = $this->queryColumn("g.idx('atoms')[['atom':'Ternary']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['String'] = $this->queryColumn("g.idx('atoms')[['atom':'String']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Arguments'] = $this->queryColumn("g.idx('atoms')[['atom':'Arguments']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['PPP'] = $this->queryColumn("g.idx('atoms')[['atom':'_Ppp']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['no_fullcode'] = $this->queryColumn("g.V.has('fullcode', null).except([g.v(0)]).hasNot('index', 'true').filter{!(it.token in ['INDEX', 'T_ROOT', 'T_END', 'E_CLASS', 'E_FUNCTION', 'E_NAMESPACE'])}.transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");

        if ($config->format == 'Json') {
            $output = json_encode($stats);
        } elseif ($config->format == 'Table') {
            $output = $this->table_encode($stats);
        } else {
            $output = $this->text_encode($stats);
        }
        
        print $output;
    }

    private function queryOne($query) {
        $r = query($query);
        return $r[0][0];
    }

    private function queryArray($query) {
        $return = array();
    
        $result = query($query);
        foreach($result as $r) {
            $return[] = (array) $r[0];
        }
    
        return $return;
    }

    private function queryColumn($query) {
        $return = array();
    
        $result = $this->query($query);
        foreach($result as $r) {
            $return[] = $r[0];
        }
    
        return $return;
    }

    private function query($querystring) {
        $params = array('type' => 'IN');
        try {
            $query = new Gremlin\Query($this->client, $querystring, $params);
            return $query->getResultSet();
        } catch (Exception $e) {
            print "'$querystring' failed\n";
            return array();
        }
    }

    private function table_encode($stats) {
        $html = "<html><body>";
    
        foreach($stats as $name => $value) {
            $html .= "<tr><td>$name</td><td>$value</td></tr>\n";
        }
    
        $html .= "</body></html>";
        return $html;
    }

    private function text_encode($stats) {
        $html = "Statistics for the whole server\n\n";
    
        foreach($stats as $name => $value) {
            if (is_array($value)) {
                $html .= "$name : ".join(" \n".str_repeat(' ', strlen("$name : ")), $value)."\n";
            } else {
                $html .= "$name : $value\n";
            }
        }
    
        $html .= "\n";
        return $html;
    }
}

?>