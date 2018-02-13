<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Analyzer\Analyzer;
use Exakat\Datastore;
use Exakat\Dump;

abstract class Reports {
    const FILE_EXTENSION = 'undefined';
    const FILE_NAME      = 'undefined';
    
    const STDOUT = 'stdout';

    static public $FORMATS        = array('Ambassador', 'AmbassadorNoMenu', 'Devoops', 'Drillinstructor',
                                          'Text', 'Xml', 'Uml', 'PlantUml', 'None', 'SimpleHtml',
                                          'PhpConfiguration', 'PhpCompilation',
                                          'Inventories', 'Clustergrammer', 'FileDependencies', 'FileDependenciesHtml',
                                          'ZendFramework',  'CodeSniffer', 'Slim',
                                          'RadwellCode', 'Melis',
                                          'FacetedJson', 'Json', 'OnepageJson', 'Marmelab', 'Simpletable',
                                          'Codeflower', 'Dependencywheel',
                                          );

    protected $themesToShow = array('CompatibilityPHP56', //'CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55',
                                    'CompatibilityPHP70', 'CompatibilityPHP71', 'CompatibilityPHP72', 'CompatibilityPHP73',
                                    '"Dead code"', 'Security', 'Analyze', 'Inventories');

    private $count = 0;

    protected $themes     = array(); // cache for themes list
    protected $themesList = '';      // cache for themes list in SQLITE
    protected $config     = null;

    protected $sqlite = null;
    protected $datastore = null;

    public function __construct($config) {
        $this->config = $config;

        $analyzers = Analyzer::getThemeAnalyzers($this->config->thema);
        $this->themesList = '("'.implode('", "', $analyzers).'")';

        $this->sqlite = new \Sqlite3($this->config->projects_root.'/projects/'.$this->config->project.'/dump.sqlite', \SQLITE3_OPEN_READONLY);

        $this->datastore = new Dump($this->config);
    }

    abstract public function generate($dirName, $fileName);

    protected function count($step = 1) {
        $this->count += $step;
    }

    public function getCount() {
        return $this->count;
    }
}

?>