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

namespace Exakat\Tasks;

class Export extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run(): void {
        $gremlinVersion = $this->gremlin->serverInfo()[0];

        if (version_compare($gremlinVersion, '3.4.0') >= 0) {
            $queryTemplate = 'g.V().valueMap().with(WithOptions.tokens).by(unfold())';
        } else {
            $queryTemplate = 'g.V()';
        }

        $vertices = $this->gremlin->query($queryTemplate, array());

        $V = array();
        $root = 0;
        foreach($vertices as $v) {
            if ($v['label'] === 'Project') {
                $root = $v['id'];
            }
            $V[$v['id']] =  $v;
        }

        if (version_compare($gremlinVersion, '3.4.0') >= 0) {
            $queryTemplate = 'g.E().as("e").outV().as("outV").select("e").inV().as("inV").select("e", "inV", "outV").by(valueMap(true).by(unfold())).by(id()).by(id())';
        } else {
            $queryTemplate = 'g.E()';
        }
        $edges = $this->gremlin->query($queryTemplate);

        $E = array();
        foreach($edges as $e) {
            // Special for version 3.4
            if (isset($e['e'])) {
                $e = array_merge($e, $e['e']);
            }
            $id = $e['outV'];

            if (!isset($E[$id])) {
                $E[$id] = array();
            }

            $endNodeId = $e['inV'];
            if(isset($E[$id][$endNodeId])) {
                $E[$id][$endNodeId] .= ', ' . $e['label'];
            } else {
                $E[$id][$endNodeId] = $e['label'];
            }
        }

        if (in_array('Dot', $this->config->project_reports)) {
            $text = $this->display_dot($V, $E, $root);
        } elseif (in_array('Table', $this->config->project_reports)) {
            $text = $this->display_table($V, $E, $root);
        } else {
            $text = $this->display_text($V, $E, $root);
        }

        if ($this->config->filename) {
            if (in_array('Dot', $this->config->project_reports)) {
                $fp = fopen($this->config->filename . '.dot', 'w+');
            } else {
                $fp = fopen($this->config->filename, 'w+');
            }
            fwrite($fp, $text);
            fclose($fp);
        } else {
            echo $text;
        }
    }

    private function display_text(array $V, array $E, int $root, $level = 0) {
        $r = array();

        if (isset($V[$root])) {
            $r []= str_repeat('  ', $level) . $V[$root]['code'];
        }

        if (isset($E[$root])) {
            asort($E[$root]);
            uksort($E[$root], function (int $a, int $b) use ($V) {
                if (!isset($V[$a]['rank'])) { return 0; }
                if (!isset($V[$b]['rank'])) { return 0; }
                return $V[$a]['rank'] > $V[$b]['rank']; });

            foreach($E[$root] as $id => $label) {
                $r []= str_repeat('  ', $level) . 'Label : ' . $label . "\n" . $this->display_text($V, $E, $id, $level + 1);
            }
        }

        return implode(PHP_EOL, $r);
    }

    private function display_dot(array $V, array $E, int $root): string {
        $r = '';

        foreach($V as $id => $v) {
            if (!isset($v['fullcode'])) {
                if (isset($v['code'])) {
                    $v['fullcode'] =  $v['code'];
                } elseif (isset($v['analyzer'])) {
                    $v['fullcode'] =  $v['analyzer'];
                } else {
                    $v['fullcode'] =  'NO CODE PROVIDED';
                }
            }
            $R = $id . ' [label="' . addslashes($v['fullcode']) . '"';

        //https://sashat.me/2017/01/11/list-of-20-simple-distinct-colors/
        //        #e6194B, #3cb44b, #ffe119, #4363d8, #f58231, #911eb4, #42d4f4, #f032e6, #bfef45, #fabebe, #469990, #e6beff, #9A6324, #fffac8, #800000, #aaffc3, #808000, #ffd8b1, #000075, #a9a9a9, #ffffff, #000000

            switch($v['label']) {
                case 'Variable' :
                case 'This' :
                case 'Variableobject' :
                case 'Variablearray' :
                    $R .= ' style="filled" fillcolor="#e6194B"';
                    break;

                case 'Functioncall' :
                case 'Methodcall' :
                case 'Staticmethodcall' :
                    $R .= ' style="filled" fillcolor="#3cb44b"';
                    break;

                case 'Class' :
                    $R .= ' style="filled" fillcolor="#ffe119"';
                    break;

                case 'Interface' :
                    $R .= ' style="filled" fillcolor="#4363d8"';
                    break;

                case 'Trait' :
                    $R .= ' style="filled" fillcolor="#911eb4"';
                    break;

                case 'Method' :
                case 'Magicmethod' :
                    $R .= ' style="filled" fillcolor="#42d4f4"';
                    break;

                default:
                    // nothing, really
            }

            if (isset($v['atom'])) {
                $R .= ' shape=box ';
            }
            $R .= "];\n";

            $r .= $R;
        }

        foreach($E as $start => $e) {
            foreach($e as $end => $label) {
                $r .= "$start -> $end [label=\"$label\"];\n";
            }
        }

        $r = " digraph graphname {
    $r
     }";

        return $r;
    }

    private function display_table(array $V, array $E, int $root): string {
        $r = '<table>';

        foreach($V as $v) {
            $row = array(highlight_string($v['code'], \RETURN_VALUE));
            if (isset($v['atom'])) {
                $row[] = $v['atom'];
            } else {
                $row[] = 'No atom';
            }
            if (isset($v['token'])) {
                $row[] = $v['token'];
            } else {
                $row[] = 'No token';
            }
            if (isset($v['file'])) {
                $row[] = $v['file'];
            } else {
                $row[] = 'No file';
            }
            if (isset($v['order'])) {
                $row[] = $v['order'];
            } else {
                $row[] = '';
            }

            $row = '<td>' . implode('</td><td>', $row) . '</td>';
            $r .= "<tr>$row</tr>\n";
        }
        $r .= '</table>';

        return $r;
    }
}

?>
