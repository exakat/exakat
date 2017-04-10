<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

use Exakat\Analyzer\Analyzer;
use Exakat\Config;
use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Exceptions\NeedsAnalyzer;
use Exakat\Reports\Reports;
use Exakat\Tokenizer\Token;

class Results extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $analyzer = $this->config->program;

        if (empty($analyzer)) {
            throw new NeedsAnalyzer();
        }

        $analyzerClass = Analyzer::getClass($analyzer);

        if ($analyzerClass === false) {
            throw new NoSuchAnalyzer($analyzer);
        }

        $analyzer = Analyzer::getName($analyzerClass);

        $query = <<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer", "$analyzer").out().count();
GREMLIN;

        // This should be a valid result, not processed as a die
        $vertices = $this->gremlin->query($query)->results;
        if (isset($vertices[0]->notCompatibleWithPhpVersion)) {
            die($this->config->program." is not compatible with the running version of PHP. No result available.\n");
        }

        if (isset($vertices[0]->notCompatibleWithPhpConfiguration)) {
            die($this->config->program." is not compatible with the compilation of the running version of PHP. No result available.\n");
        }

        $return = array();
        if ($this->config->style == 'BOOLEAN') {
            $queryTemplate = 'g.V().hasLabel("Analysis").has("analyzer", "'.$analyzer.'").out().count().is(gt(0))';
            $vertices = $this->gremlin->query($queryTemplate);

            $return[] = $vertices[0];
        } elseif ($this->config->style == 'COUNTED_ALL') {
            $queryTemplate = 'g.V().hasLabel("Analysis").has("analyzer", "'.$analyzer.'").out().count()';
            $vertices = $this->gremlin->query($queryTemplate)->results;

            $return[] = $vertices[0];
        } elseif ($this->config->style == 'ALL') {
            $linksDown = Token::linksAsList();

            $query = <<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer", "{$analyzer}").out('ANALYZED')
.sideEffect{ line = it.get().value('line');
             fullcode = it.get().value('fullcode');
             file='None'; 
             theFunction = 'None'; 
             theClass='None'; 
             theNamespace='None'; 
             }
.sideEffect{ line = it.get().value('line'); }
.until( hasLabel('Project') ).repeat( 
    __.in($linksDown)
      .sideEffect{ if (it.get().label() == 'Function') { theFunction = it.get().value('code')} }
      .sideEffect{ if (it.get().label() == 'Class') { theClass = it.get().value('fullcode')} }
      .sideEffect{ if (it.get().label() == 'File') { file = it.get().value('fullcode')} }
       )

.map{ ['line':line, 'file':file, 'fullcode':fullcode, 'function':theFunction, 'class':theClass, 'namespace':theNamespace]; }
GREMLIN;

            $vertices = $this->gremlin->query($query)->results;

            $return = array();
            foreach($vertices as $k => $v) {
                $row = array($v->fullcode,
                             $v->file,
                             $v->line,
                             $v->namespace,
                             $v->class,
                             $v->function);
                $return[] = $row;
            }
        } elseif ($this->config->style == 'DISTINCT') {
            $queryTemplate = 'g.V().hasLabel("Analysis").has("analyzer", "'.$analyzer.'").out("ANALYZED").values("code").unique()';
            $vertices = $this->gremlin->query($queryTemplate)->results;

            $return = array();
            foreach($vertices as $k => $v) {
                $return[] = array($v);
            }
        } elseif ($this->config->style == 'COUNTED') {
            $queryTemplate = 'g.V().hasLabel("Analysis").has("analyzer", "'.$analyzer.'").out("ANALYZED").groupCount("m")by("code").cap("m")';
            $vertices = $this->gremlin->query($queryTemplate)->results;

            $return = array();
            foreach($vertices[0] as $k => $v) {
                $return[$k] = $v;
            }
        }

        if ($this->config->json === true) {
            $text = json_encode($return);
        } elseif ($this->config->csv === true) {
            $text = array(array('Code', 'File', 'Namespace', 'Class', 'Function'));
            foreach($return as $k => $v) {
                if (is_array($v)) {
                    $text[] = $v;
                } else {
                    $text[] = array($k, $v);
                }
            }
        } elseif ($this->config->html === true || $this->config->odt === true) {
            $text = '';
            foreach($return as $k => $r) {
                if ($this->config->style == 'COUNTED') {
                    $text .= "+ $k => $r\n";
                } else {
                    $text .= "+ $k\n";
                    if (is_array($r)) {
                        $text .= '  + '.implode("\n  + ", $r)."\n";
                    } else {
                        $text .= "+ $r\n";
                    }
                }
            }
        } else {
            // count also for $this->config->text == 1
            $text = '';
            foreach($return as $k => $v) {
                if ($this->config->style == 'COUNTED') {
                    $text .= "$k => $v\n";
                } else {
                    $text .= implode(', ', $v)."\n";
                }
            }
        }

        if ($this->config->output) {
            echo $text;
        }

        switch (1) {
            case $this->config->json :
                $extension = 'json';
                break 1;
            case $this->config->odt :
                $extension = 'odt';
                break 1;
            case $this->config->html :
                $extension = 'html';
                break 1;
            case $this->config->csv :
                $extension = 'csv';
                break 1;
            case $this->config->text :
            default :
                $extension = 'txt';
                break 1;
        }

        if ($this->config->file != Reports::STDOUT) {
            $name = $this->config->file.'.'.$extension;
            if (file_exists($name)) {
                die( "$name already exists. Aborting\n");
            }

            if ($this->config->format == 'CSV') {
                $csvFile = fopen($name, 'w');
                foreach($text as $t) {
                    fputcsv($csvFile, $t);
                }
                fclose($csvFile);
            } else {
                file_put_contents($name, $text);
            }
        }
    }
}

?>
