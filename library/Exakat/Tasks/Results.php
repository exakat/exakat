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

use Exakat\Exceptions\NoSuchAnalyzer;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\InvalidProjectName;
use Exakat\Exceptions\ProjectNotInited;
use Exakat\Exceptions\NoDump;
use Exakat\Exceptions\NeedsAnalyzerThema;

class Results extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run(): void {
        if ($this->config->project->isDefault()) {
            throw new ProjectNeeded();
        }

        if (!$this->config->project->validate()) {
            throw new InvalidProjectName($this->config->project->getError());
        }

        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($this->config->project);
        }

        if (!file_exists($this->config->datastore)) {
            throw new ProjectNotInited($this->config->project);
        }

        if (!file_exists($this->config->dump)) {
            throw new NoDump((string) $this->config->project);
        }

        if (!empty($this->config->program)) {
            if (is_array($this->config->program)) {
                $analyzersClass = $this->config->program;
            } else {
                $analyzersClass = array($this->config->program);
            }

            foreach($analyzersClass as $analyzer) {
                if (!$this->rulesets->getClass($analyzer)) {
                    throw new NoSuchAnalyzer($analyzer, $this->rulesets);
                }
            }
        } elseif (!empty($this->config->project_rulesets)) {
            $project_rulesets = $this->config->project_rulesets;

            if (!$analyzersClass = $this->rulesets->getRulesetsAnalyzers($project_rulesets)) {
                throw new NoSuchAnalyzer($project_rulesets, $this->rulesets);
            }
        } else {
            throw new NeedsAnalyzerThema();
        }

        foreach($analyzersClass as $id => $analyzerClass) {
            if (substr($analyzerClass, 0, 4) === 'Ext/') {
                $analyzer = $this->rulesets->getInstance($analyzerClass, $this->gremlin, $this->config);
                $analyzerList = $analyzer->getAnalyzerList();

                unset($analyzersClass[$id]);
                if (!empty($analyzerList)) {
                    $analyzersClass = array_merge($analyzersClass, $analyzerList);
                }
            }
        }

        $return = array();
        if ($this->config->style === 'BOOLEAN') {
            $queryTemplate = <<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer", "$analyzer").out().count().is(gt(0))
GREMLIN;
            $vertices = $this->gremlin->query($queryTemplate);

            $return[] = $vertices[0];
        } elseif ($this->config->style === 'COUNTED_ALL') {
            $queryTemplate = <<<GREMLIN
g.V().hasLabel("Analysis").has("analyzer", "$analyzer").out().count()
GREMLIN;
            $vertices = $this->gremlin->query($queryTemplate)->results;

            $return[] = $vertices[0];
        } elseif ($this->config->style === 'ALL') {
            $results = array();

            foreach($analyzersClass as $oneAnalyzerClass) {
                $analyzer =  $this->rulesets->getInstance($oneAnalyzerClass, null, $this->config);
                $results[] = $analyzer->getDump();
            }

            $return = array_merge(...$results);
        } elseif ($this->config->style === 'DISTINCT') {
            $queryTemplate = 'g.V().hasLabel("Analysis").has("analyzer", "' . $analyzer . '").out("ANALYZED").values("code").unique()';
            $vertices = $this->gremlin->query($queryTemplate)->results;

            $return = array();
            foreach($vertices as $values) {
                $return[] = array($values);
            }
        } elseif ($this->config->style === 'COUNTED') {
            $queryTemplate = 'g.V().hasLabel("Analysis").has("analyzer", "' . $analyzer . '").out("ANALYZED").groupCount("m").by("code").cap("m")';
            $vertices = $this->gremlin->query($queryTemplate)->results;

            $return = array();
            foreach($vertices[0] as $k => $values) {
                $return[$k] = $values;
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
                if ($this->config->style === 'COUNTED') {
                    $text .= "+ $k => $r\n";
                } else {
                    $text .= "+ $k\n";
                    if (is_array($r)) {
                        $text .= '  + ' . implode("\n  + ", $r) . "\n";
                    } else {
                        $text .= "+ $r\n";
                    }
                }
            }
        } else {
            // count also for $this->config->text == 1
            $text = '';
            foreach($return as $k => $v) {
                if ($this->config->style === 'COUNTED') {
                    $text .= "$k => $v\n";
                } else {
                    $text .= implode(', ', $v) . "\n";
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

        if ($this->config->file != '') {
            $name = $this->config->file . '.' . $extension;
            if (file_exists($name)) {
                die( "$name already exists. Aborting\n");
            }

            if ($this->config->format === 'CSV') {
                $csvFile = fopen($name, 'w');
                if (is_resource($csvFile)) {
                    foreach($text as $t) {
                        fputcsv($csvFile, $t);
                    }
                    fclose($csvFile);
                } else {
                    die( "Couldn't open $name file for writing. Aborting\n");
                }
            } else {
                file_put_contents($name, $text);
            }
        }
    }
}

?>
