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

class Errors extends Tasks {
    public function run(\Config $config) {

        $stats = array();

        $stats['files with next'] = gremlin_queryColumn("g.idx('racines')[['token':'ROOT']].out('INDEXED').out('NEXT').in.loop(1){true}{ it.object.token == 'T_FILENAME'}.code");
        $stats['Function with next'] = gremlin_queryColumn("g.idx('atoms')[['atom':'_Function']].out('INDEXED').out('NEXT').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Switch'] = gremlin_queryColumn("g.idx('atoms')[['atom':'_Switch']].out('INDEXED').filter{it.out('NEXT').any()}.transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Addition'] = gremlin_queryColumn("g.idx('atoms')[['atom':'Addition']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['New'] = gremlin_queryColumn("g.idx('atoms')[['atom':'_New']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Property'] = gremlin_queryColumn("g.idx('atoms')[['atom':'Property']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Not'] = gremlin_queryColumn("g.idx('atoms')[['atom':'Not']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Keyvalue'] = gremlin_queryColumn("g.idx('atoms')[['atom':'Keyvalue']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Ternary'] = gremlin_queryColumn("g.idx('atoms')[['atom':'Ternary']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['String'] = gremlin_queryColumn("g.idx('atoms')[['atom':'String']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['Arguments'] = gremlin_queryColumn("g.idx('atoms')[['atom':'Arguments']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['PPP'] = gremlin_queryColumn("g.idx('atoms')[['atom':'_Ppp']].out('INDEXED').transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");
        $stats['no_fullcode'] = gremlin_queryColumn("g.V.has('fullcode', null).except([g.v(0)]).hasNot('index', 'true').filter{!(it.token in ['INDEX', 'T_ROOT', 'ROOT', 'T_END'])}.transform{it.in.loop(1){true}{ it.object.token == 'T_FILENAME'}.next().code + ' ' + it.line }");

        if ($config->format == 'Json') {
            $output = json_encode($stats);
        } elseif ($config->format == 'Table') {
            $output = $this->table_encode($stats);
        } else {
            $output = $this->text_encode($stats);
        }
        
        echo $output;
    }

    private function table_encode($stats) {
        $html = '<html><body>';
    
        foreach($stats as $name => $value) {
            $html .= '<tr><td>'.$name.'</td><td>'.$value."</td></tr>\n";
        }
    
        $html .= '</body></html>';
        return $html;
    }

    private function text_encode($stats) {
        $html = "Statistics for the whole server\n\n";
    
        foreach($stats as $name => $value) {
            if (is_array($value)) {
                $html .= $name." : ".join(" \n".str_repeat(' ', strlen($name.' : ')), $value)."\n";
            } else {
                $html .= $name.' : '.$value."\n";
            }
        }
    
        $html .= "\n";
        return $html;
    }
}

?>
