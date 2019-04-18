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

use Exakat\Analyzer\Analyzer;

class Uml extends Reports {
    const FILE_EXTENSION = 'dot';
    const FILE_FILENAME  = 'exakat.uml';

    public function _generate($analyzerList) {
        $res = $this->sqlite->query(<<<SQL
SELECT name, cit.id, extends, type, namespace, 
       (SELECT GROUP_CONCAT(method,   "||")   FROM methods    WHERE citId = cit.id) AS methods,
       (SELECT GROUP_CONCAT( case when value != '' then property || " = " || substr(value, 0, 40) else property end, "||") FROM properties WHERE citId = cit.id) AS properties
    FROM cit
    JOIN namespaces
        ON namespaces.id = cit.namespaceId
SQL
        );
        $id = 0;
        $ids = array();
        $dot = array();
        $links = array();
        $colors = array('class'     => 'darkorange',
                        'trait'     => 'gold',
                        'interface' => 'skyblue',
                        'native'    => 'brown1',
                        );
        $subgraphs = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            ++$id;
            if (empty($row['properties'])) {
                $row['properties'] = '+&nbsp;'.str_replace('||', "<br align='left'/>+&nbsp;", $this->str2dot($row['properties']))."<br align='left'/>";
            } elseif ($row['type'] === 'interface') {
                $row['properties'] = '&nbsp;';
            } else {
                $row['properties'] = '<i>No properties</i>';
            }
            if (empty($row['methods'])) {
                $row['methods'] = '+&nbsp;'.str_replace('||', "<br align='left'/>+&nbsp;", $this->str2dot($row['methods']))."<br align='left'/>";
            } else {
                $row['methods'] = '<i>No methods</i>';
            }
            $color = $colors[$row['type']];
            $label = "<<table color='white' BORDER='0' CELLBORDER='1' CELLSPACING='0' >
                          <tr>
                              <td bgcolor='$color' color='black'>$row[name]</td>
                          </tr>
                          <tr>
                              <td color='black' align='left'>$row[properties]</td>
                          </tr>
                          <tr>
                              <td color='black' align='left'>$row[methods]</td>
                          </tr>
                       </table>>";
            $R = $id.' [label='.$label.' shape="none"];';

            $ids[$row['id']] = $id;
            $subgraphs[$row['namespace']] = $R;

            $N = explode('\\', $row['namespace']);
            $dotr = &$dot;
            foreach($N as $n) {
                if (!isset($dotr[$n])) {
                    $dotr[$n] = array();
                }
                $dotr = &$dotr[$n];
            }
            $dotr[] = $R;

            if (!empty($row['extends'])) {
                $links[] = " $id -> \"$row[extends]\" [label=\"extends\"];";
            }
        }

        $res = $this->sqlite->query(<<<SQL
SELECT implementing, implements, type FROM cit_implements
SQL
        );
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!isset($ids[$row['implements']])) {
                $ids[$row['implements']] = $row['implements'];
            }
            $links[] = "{$ids[$row['implementing']]} -> \"{$ids[$row['implements']]}\" [label=\"$row[type]\"];";
        }

        $dot = <<<DOT
        digraph graphname {        
        fontname = "Bitstream Vera Sans"
        fontsize = 8
        colorscheme = "bugn9"
    
        node [
            fontname = "Bitstream Vera Sans"
            fontsize = 8
            shape = "record"
        ]
    
        edge [
            fontname = "Bitstream Vera Sans"
            fontsize = 8
            arrowhead = "empty"
        ]
 
DOT
        .$this->subgraphs($dot)."\n\n".implode("\n", $links)."\n}\n";
    
        return $dot;
    }

    private function str2dot($str) {
        return htmlspecialchars($str, ENT_COMPAT | ENT_HTML401 , 'UTF-8');
    }

    private function subgraphs($array, $level = 1, $nsname = '') {
        static $id = 0;
        $r = '';

        // Colors are managed with $level, thanks to colorscheme option.
        foreach($array as $key => $a) {
            ++$id;
            if (is_int($key)) {
                $r .= $a;
            } else {
                $nextName = "$nsname$key\\";
                $nextNameDot = addslashes($nextName);
                $r .= "subgraph cluster_$id { 
        style=filled;
        label=\"$nextNameDot\";
        color=\"$level\";
        ";
                $r .= $this->subgraphs($a, $level + 1, $nextName);
                $r .= "}\n";
            }
        }

        return $r;
    }
}

?>