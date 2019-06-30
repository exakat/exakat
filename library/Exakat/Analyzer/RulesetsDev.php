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

class RulesetsDev {
    private $dev           = null;
    private $all           = array('All' => array());
    private $rulesets      = array();

    public function __construct(AutoloadDev $dev) {
        $this->dev = $dev;
        
        $this->all      = $dev->getAllAnalyzers() ?: array('All' => array());
        $this->rulesets = array_keys($this->all);
    }
    
    public function getSuggestionRuleset(array $ruleset) {
        return array_filter($this->rulesets, function ($c) use ($ruleset) {
            foreach($ruleset as $r) {
                $l = levenshtein($c, $r);
                if ($l < 8) {
                    return true;
                }
            }
            return false;
        });
    }

    public function getRulesetsAnalyzers(array $ruleset = null) {
        if (empty($ruleset)) {
            return array();
        }
        
        $return = array();
        foreach($ruleset as $t) {
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
    
    public function getRulesetsForAnalyzer($analyzer = null) {
        $return = array();

        if ($analyzer === null) {
            $list = $this->all;
            $return = array_fill_keys($list['All'], array());
            unset($list['All']);
            
            foreach($list as $rulesets => $ruleset) {
                foreach($ruleset as $rule) {
                    $return[$rule][] = $rulesets;
                }
            }
        } else {
            foreach($this->all as $rulesets => $ruleset) {
                if (in_array($analyzer, $ruleset, STRICT_COMPARISON)) {
                    $return[] = $rulesets;
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
