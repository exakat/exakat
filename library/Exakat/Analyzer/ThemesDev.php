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
use Exakat\Autoload\AutoloadDev;

class ThemesDev {
    private $dev           = null;
    private $all           = array();
    private $themes        = array();

    public function __construct(AutoloadDev $dev) {
        $this->dev = $dev;
        
        $this->all = $dev->getAllAnalyzers();
        $this->themes = array_keys($this->all);
    }
    
    public function getSuggestionThema(array $thema) {
        return array_filter($this->themes, function ($c) use ($thema) {
            foreach($thema as $theme) {
                $l = levenshtein($c, $theme);
                if ($l < 8) {
                    return true;
                }
            }
            return false;
        });
    }

    public function getThemeAnalyzers(array $theme = null) {
        if (empty($theme)) {
            return array();
        }
        
        $return = array();
        foreach($theme as $t) {
            $return[] = $this->all[$t] ?? array();
        }

        return array_merge(...$return);
    }

    public function getAnalyzerInExtension($name) {
        if (!isset($this->all['All'])) {
            return array();
        }
        return preg_grep("#/$name\$#", $this->all['All']);
    }
    
    public function getThemesForAnalyzer($analyzer = null) {

        if ($analyzer === null) {
            $list = $this->all;
            $return = array_fill_keys($list['All'], array());
            unset($list['All']);
            
            foreach($list as $theme => $ruleset) {
                foreach($ruleset as $rule) {
                    $return[$rule][] = $theme;
                }
            }
        } else {
            foreach($this->all as $theme => $ruleset) {
                if (in_array($analyzer, $ruleset, STRICT_COMPARISON)) {
                    $return[] = $theme;
                }
            }
        }
        
        return $return;
    }

    public function getSeverities() {
        return array_fill_keys($this->all['All'], Analyzer::S_NONE);
    }

    public function getTimesToFix() {
        return array_fill_keys($this->all['All'], Analyzer::T_NONE);
    }
}
?>
