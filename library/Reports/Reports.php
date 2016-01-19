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

namespace Reports;

abstract class Reports {
    private $count = 0;

    CONST FILE_EXTENSION = 'undefined';
    CONST FORMATS        = ['Devoops', 'Faceted', 'FacetedJson', 'Json', 'OnepageJson', 'Text', 'Xml'];

    protected $themes     = array(); // cache for themes list
    protected $themesList = '';      // cache for themes list in SQLITE
    protected $config     = null;
    
    public function __construct() {
        $this->themes = array_merge(\Analyzer\Analyzer::getThemeAnalyzers('Analyze'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('Dead Code'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('Security'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP53'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP54'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP55'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP56'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP70'),
                                    \Analyzer\Analyzer::getThemeAnalyzers('CompatibilityPHP71')
                                    );
        $this->themesList = '("'.implode('", "', $this->themes).'")';
        
        $this->config = \Config::Factory();
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
