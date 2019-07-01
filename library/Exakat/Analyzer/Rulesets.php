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


namespace Exakat\Analyzer;

use Exakat\Analyzer\Analyzer;
use Exakat\Autoload\AutoloadExt;
use Exakat\Autoload\AutoloadDev;

class Rulesets {
    private $main   = null;
    private $ext    = null;
    private $extra  = array();
    private $dev    = null;

    private static $instanciated = array();

    public function __construct($path, AutoloadExt $ext, AutoloadDev $dev, array $extra_rulesets = array()) {
        $this->main  = new RulesetsMain($path);
        $this->ext   = new RulesetsExt($ext, $ext);
        $this->extra = new RulesetsExtra($extra_rulesets, $ext);
        $this->dev   = new RulesetsDev($dev, $dev);
    }

    public function __destruct() {
        $this->main  = null;
        $this->ext   = null;
        $this->extra = null;
    }
    
    public function getRulesetsAnalyzers(array $theme = null) {
        $main  = $this->main ->getRulesetsAnalyzers($theme);
        $extra = $this->extra->getRulesetsAnalyzers($theme);
        $ext   = $this->ext  ->getRulesetsAnalyzers($theme);
        $dev   = $this->dev  ->getRulesetsAnalyzers($theme);
        
        return array_merge($main, $extra, $ext, $dev);
    }

    public function getRulesetForAnalyzer($analyzer) {
        $main = $this->main  ->getRulesetForAnalyzer($analyzer);
        $extra = $this->extra->getRulesetForAnalyzer($analyzer);
        $ext   = $this->ext  ->getRulesetForAnalyzer($analyzer);
        $dev   = $this->dev  ->getRulesetForAnalyzer($analyzer);

        return array_merge($main, $extra, $ext, $dev);
    }

    public function getRulesetsForAnalyzer($list = null) {
        $main  = $this->main ->getRulesetsForAnalyzer($list);
        $extra = $this->extra->getRulesetsForAnalyzer($list);
        $ext   = $this->ext  ->getRulesetsForAnalyzer($list);
        $dev   = $this->dev  ->getRulesetsForAnalyzer($list);
        
        return array_merge($main, $extra, $ext, $dev);
    }

    public function getSeverities() {
        $main  = $this->main ->getSeverities();
        $extra = $this->extra->getSeverities();
        $ext   = $this->ext  ->getSeverities();
        $dev   = $this->dev  ->getSeverities();
        
        return array_merge($main, $extra, $ext, $dev);
    }

    public function getTimesToFix() {
        $main  = $this->main ->getTimesToFix();
        $extra = $this->extra->getTimesToFix();
        $ext   = $this->ext  ->getTimesToFix();
        $dev   = $this->dev  ->getTimesToFix();
        
        return array_merge($main, $extra, $ext, $dev);
    }

    public function getFrequences() {
        $main = $this->main->getFrequences();

        return array_merge($main);
    }
    
    public function listAllAnalyzer($folder = null) {
        $main  = $this->main ->listAllAnalyzer($folder);
        $extra = $this->extra->listAllAnalyzer($folder);
        $ext   = $this->ext  ->listAllAnalyzer($folder);
        $dev   = $this->dev  ->listAllAnalyzer($folder);

        return array_merge($main, $extra, $ext, $dev);
    }

    public function listAllRulesets($theme = null) {
        $main  = $this->main ->listAllRulesets($theme);
        $extra = $this->extra->listAllRulesets($theme);
        $ext   = $this->ext  ->listAllRulesets($theme);
        $dev   = $this->dev  ->listAllAnalyzer($theme);
        
        return array_merge($main, $extra, $ext, $dev);
    }

    public function getClass($name) {
        if ($class = $this->main->getClass($name)) {
            return $class;
        }

        if ($class = $this->extra->getClass($name)) {
            return $class;
        }

        if ($class = $this->ext->getClass($name)) {
            return $class;
        }
        
        return false;
    }

    public function getSuggestionThema(array $theme) {
        $main  = $this->main ->getSuggestionThema($theme);
        $extra = $this->extra->getSuggestionThema($theme);
        $ext   = $this->ext  ->getSuggestionThema($theme);
        $dev   = $this->dev  ->getSuggestionThema($theme);
        
        return array_merge($main, $extra, $ext, $dev);
    }
    
    public function getSuggestionClass($name) {
        $main  = $this->main ->getSuggestionClass($name);
        $extra = $this->extra->getSuggestionClass($name);
        $ext   = $this->ext  ->getSuggestionClass($name);
        $dev   = $this->dev  ->getSuggestionClass($name);
        
        return array_merge($main, $extra, $ext, $dev);
    }

    public function getAnalyzerInExtension($name) {
//        $main  = $this->main ->getAnalyzerInExtension($name);
//        $extra = $this->extra->getAnalyzerInExtension($name);
        $ext   = $this->ext  ->getAnalyzerInExtension($name);
        $dev   = $this->dev  ->getAnalyzerInExtension($name);
        
//        return array_merge($main, $extra, $ext, $dev);
        return array_merge($ext, $dev);
    }

    public static function resetCache() {
        self::$instanciated = array();
    }
    
    public function getInstance($name, $gremlin = null, $config = null) {
        if ($analyzer = $this->getClass($name)) {
            if (!isset(self::$instanciated[$analyzer])) {
                self::$instanciated[$analyzer] = new $analyzer($gremlin, $config);
            }
            return self::$instanciated[$analyzer];
        } else {
            display("No such class as '$name'");
            return null;
        }
    }

}
?>
