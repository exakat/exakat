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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;

abstract class Reports {
    private $count = 0;

    CONST FILE_EXTENSION = 'undefined';
    CONST FORMATS        = ['Clustergrammer', 'Devoops', 'Faceted', 'FacetedJson', 'Json', 'OnepageJson', 
                            'Text', 'Xml', 'Uml', 'ZendFramework', 'Ambassador'];

    protected $themes     = array(); // cache for themes list
    protected $themesList = '';      // cache for themes list in SQLITE
    protected $config     = null;
    
    protected $sqlite = null;
    protected $datastore = null;
    
    public function __construct() {
        $this->config = \Exakat\Config::Factory();

        $analyzers = Analyzer::getThemeAnalyzers($this->config->thema);
        $this->themesList = '("'.implode('", "', $analyzers).'")';

        $this->sqlite = new \Sqlite3($this->config->projects_root.'/projects/'.$this->config->project.'/dump.sqlite', SQLITE3_OPEN_READONLY);

        $this->datastore = new \sqlite3($this->config->projects_root.'/projects/'.$this->config->project.'/datastore.sqlite', SQLITE3_OPEN_READONLY);
    }
    
    public abstract function generateFileReport($report);

    public abstract function generate($dirName, $fileName);
    
    protected function count($step = 1) {
        $this->count += $step;
    }
    
    public function getCount() {
        return $this->count;
    }
}
