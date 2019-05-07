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
use Exakat\Analyzer\Themes;
use Exakat\Analyzer\Analyzer;
use Exakat\Datastore;
use Exakat\Dump;
use Exakat\Reports\Helpers\Docs;

abstract class Reports {
//    const FILE_EXTENSION = 'undefined';
//    const FILE_NAME      = 'undefined';
    
    const STDOUT = 'stdout';
    const INLINE = 'inline';
    
    private static $docs = null;

    public static $FORMATS        = array('Ambassador', 'Ambassadornomenu', 'Drillinstructor',
                                          'Text', 'Xml', 'Uml', 'Plantuml', 'None', 'Simplehtml', 'Owasp', 'Perfile',
                                          'Phpconfiguration', 'Phpcompilation', 'Favorites', 'Manual',
                                          'Inventories', 'Clustergrammer', 'Filedependencies', 'Filedependencieshtml',
                                          'Radwellcode', 'Grade', 'Weekly', 'Scrutinizer','Codesniffer', 'Phpcsfixer',
                                          'Facetedjson', 'Json', 'Onepagejson', 'Marmelab', 'Simpletable',
                                          'Codeflower', 'Dependencywheel', 'Phpcity',
                                          //'DailyTodo',
                                          );

    protected $themesToShow = array('CompatibilityPHP56', //'CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55',
                                    'CompatibilityPHP70', 'CompatibilityPHP71', 'CompatibilityPHP72', 'CompatibilityPHP73',
                                    '"Dead code"', 'Security', 'Analyze', 'Inventories');

    private $count = 0;

    protected $themesList = '';      // cache for themes list in SQLITE
    protected $config     = null;

    protected $sqlite    = null;
    protected $datastore = null;
    protected $themes    = null;

    public function __construct($config) {
        assert($config !== null, 'Config can\t be null');
        $this->config = $config;

        if (file_exists($this->config->dump)) {
            $this->sqlite = new \Sqlite3($this->config->dump, \SQLITE3_OPEN_READONLY);

            $this->datastore = new Dump($this->config);
            $this->themes    = new Themes("{$this->config->dir_root}/data/analyzers.sqlite",
                                          $this->config->ext,
                                          $this->config->dev,
                                          $this->config->themas);

            // Default analyzers
            $analyzers = array_merge($this->themes->getThemeAnalyzers($this->config->thema),
                                     array_keys($config->themas));
            $this->themesList = makeList($analyzers);
        }
        
        if (self::$docs === null) {
            self::$docs = new Docs($this->config->dir_root, $this->config->ext);
        }
    }

    protected function _generate($analyzerList) {}

    public static function getReportClass($report) {
        $report = ucfirst(strtolower($report));
        return "\\Exakat\\Reports\\$report";
    }
    
    public function generate($folder, $name) {
        if (empty($name)) {
            // FILE_FILENAME is defined in the children class
            $name = $this::FILE_FILENAME;
        }

        if (!empty($this->config->thema)) {
            $themas = $this->config->thema;

            if ($missing = $this->checkMissingThemes()) {
                print "Can't produce " . static::class . ' format. There are ' . count($missing) . ' missing themes : ' . implode(', ', $missing) . ".\n";
                return false;
            }

            $list = $this->themes->getThemeAnalyzers($themas);
        } elseif (!empty($this->config->program)) {
            $list = array($this->config->program);
        } else {
            $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        }

        $final = $this->_generate($list);

        if ($name === self::STDOUT) {
            echo $final ;
        } elseif ($name === self::INLINE) {
            return $final ;
        } else {
            file_put_contents($folder . '/' . $name . '.' . $this::FILE_EXTENSION, $final);
        }
    }

    protected function count($step = 1) {
        $this->count += $step;
    }

    public function getCount() {
        return $this->count;
    }

    public function dependsOnAnalysis() {
        if (empty($this->config->thema)) {
            return array();
        } else {
            return $this->config->thema;
        }
    }
    
    public function checkMissingThemes() {
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
    
    public function getDocs($analyzer, $property = null) {
        assert(self::$docs !== null, 'Docs needs to be initialized with an object.');

        if ($property === null) {
            return self::$docs->getDocs($analyzer);
        } else {
            return self::$docs->getDocs($analyzer)[$property];
        }
    }
}

?>