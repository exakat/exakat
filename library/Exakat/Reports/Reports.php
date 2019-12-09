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

use Exakat\Config;
use Exakat\Analyzer\Rulesets;
use Exakat\Analyzer\Analyzer;
use Exakat\Dump;
use Exakat\Reports\Helpers\Docs;

abstract class Reports {
    const STDOUT = 'stdout';
    const INLINE = 'inline';

    public static $FORMATS        = array('Ambassador', 'Ambassadornomenu', 'Drillinstructor', 'Top10',
                                          'Text', 'Xml', 'Uml', 'Yaml', 'Plantuml', 'None', 'Simplehtml', 'Owasp', 'Perfile', 'BeautyCanon',
                                          'Phpconfiguration', 'Phpcompilation', 'Favorites', 'Manual', 'Stubs',
                                          'Inventories', 'Clustergrammer', 'Filedependencies', 'Filedependencieshtml', 'Classdependencies', 'Stubs',
                                          'Radwellcode', 'Grade', 'Weekly', 'Scrutinizer','Codesniffer', 'Phpcsfixer',
                                          'Facetedjson', 'Json', 'Onepagejson', 'Marmelab', 'Simpletable', 'Exakatyaml',
                                          'Codeflower', 'Dependencywheel', 'Phpcity', 'Sarb',
                                          'Exakatvendors', 'Topology',
                                          //'DailyTodo',
                                          );

    protected $themesToShow = array('CompatibilityPHP56', //'CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55',
                                    'CompatibilityPHP70', 'CompatibilityPHP71', 'CompatibilityPHP72', 'CompatibilityPHP73',
                                    'CompatibilityPHP74',
                                    'CompatibilityPHP80',
                                    'Dead code', 'Security', 'Analyze', 'Inventories');

    private $count = 0;

    protected $themesList = '';      // cache for themes list in SQLITE
    protected $config     = null;
    protected $docs       = null;

    // remove sqlite, move to dump
    protected $sqlite    = null;
    protected $dump      = null;

    protected $datastore = null;
    protected $rulesets  = null;

    public function __construct() {
        $this->config    = exakat('config');
        $this->docs      = exakat('docs');
        $this->datastore = exakat('datastore');
        $this->datastore->reuse();

        if (file_exists($this->config->dump)) {
            $this->sqlite    = new \Sqlite3($this->config->dump, \SQLITE3_OPEN_READONLY);

            $this->dump      = new Dump($this->sqlite);

            $this->rulesets  = exakat('rulesets');

            // Default analyzers
            $analyzers = array_merge($this->rulesets->getRulesetsAnalyzers($this->config->project_results),
                                     array_keys($this->config->rulesets));
            $this->themesList = makeList($analyzers);
        }
    }

    protected function _generate($analyzerList) {}

    public static function getReportClass($report) {
        $report = ucfirst(strtolower($report));
        return "\\Exakat\\Reports\\$report";
    }
    
    public function generate($folder, $name = null) {
        if (empty($name)) {
            // FILE_FILENAME is defined in the children class
            $name = $this::FILE_FILENAME;
        }

        $rulesets = $this->config->project_rulesets;
        if (!empty($rulesets)) {
            if ($missing = $this->checkMissingRulesets()) {
                print "Can't produce " . static::class . ' format. There are ' . count($missing) . ' missing rulesets : ' . implode(', ', $missing) . ".\n";
                return false;
            }

            $list = $this->rulesets->getRulesetsAnalyzers($rulesets);
        } elseif (!empty($this->config->program)) {
            $list = makeArray($this->config->program);
        } else {
            $list = $this->rulesets->getRulesetsAnalyzers($this->themesToShow);
        }

        $final = $this->_generate($list);

        if ($name === self::STDOUT) {
            if (empty($final)) {
                exit(0);
            } else {
                echo $final;
                exit(1);
            }
        } elseif ($name === self::INLINE) {
            return $final ;
        } else {
            file_put_contents("$folder/$name." . $this::FILE_EXTENSION, $final);
        }
    }

    protected function count($step = 1) {
        $this->count += $step;
    }

    public function getCount() {
        return $this->count;
    }

    public function dependsOnAnalysis() : array {
        if (empty($this->config->rulesets)) {
            return array();
        } else {
            return $this->config->rulesets;
        }
    }
    
    public function checkMissingRulesets() : array {
        $required = $this->dependsOnAnalysis();

        if (empty($required)) {
            return $required;
        }

        $available = array();
        $res = $this->sqlite->query('SELECT * FROM themas');
        if ($res === false) {
            // Nothing found.
            return $required;
        }

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $available[] = $row['thema'];
        }

        return array_diff($required, $available);
    }
}

?>