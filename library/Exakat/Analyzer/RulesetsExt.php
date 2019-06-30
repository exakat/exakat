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

class RulesetsExt {
    private $ext           = null;
    private $all           = array();
    private $rulesets      = array();

    private static $instanciated = array();
    
    public function __construct(AutoloadExt $ext) {
        $this->ext = $ext;
        
        foreach($ext->getAllAnalyzers() as $name => $list) {
            if (!isset($list['All'])) {
                continue; // ignore
            }
            $this->all[$name] = $list['All'];
            unset($list['All']);
            if (!empty($list)) {
                $this->rulesets[$name] = new RulesetsExtra($list, $this->ext);
            }
        }
    }
    
    public function getRulesetsAnalyzers(array $ruleset = null) {
        if (empty($this->rulesets)) {
            return array();
        }
        
        $return = array(array());
        foreach($this->rulesets as $t) {
            $return[] = $t->getRulesetsAnalyzers($ruleset);
        }
        
        return array_merge(...$return);
    }

    public function getRulesetForAnalyzer($analyzer = null) {
        if (empty($this->rulesets)) {
            return array();
        }

        $return = array(array());
        foreach($this->rulesets as $t) {
            $return[] = $t->getRulesetForAnalyzer($analyzer);
        }

        return array_merge(...$return);
    }

    public function getRulesetsForAnalyzer($analyzer = null) {
        $return = array(array());
        foreach($this->rulesets as $extension) {
            $return[] = $extension->getRulesetsForAnalyzer($analyzer);
        }
        
        return array_merge(...$return);
    }

    public function getSeverities() {
        $return = array(array());
        foreach($this->rulesets as $extension) {
            $return[] = $extension->getSeverities();
        }
        
        return array_merge(...$return);
    }

    public function getTimesToFix() {
        $return = array(array());
        foreach($this->rulesets as $extension) {
            $return[] = $extension->getTimesToFix();
        }
        
        return array_merge(...$return);
    }

    public function getFrequences() {
        $return = array(array());
        foreach($this->rulesets as $extension) {
            $return[] = $extension->getFrequences();
        }

        return array_merge(...$return);
    }
    
    public function listAllAnalyzer($folder = null) {
        if (empty($this->all)) {
            return array();
        }

        $return = array_merge(...array_values($this->all));
        if ($folder === null) {
            return $return;
        }
        
        return preg_grep("#$folder/#", $return);
    }

    public function listAllRulesets() {
        if (empty($this->rulesets)) {
            return array();
        }

        $return = array(array());

        foreach($this->rulesets as $ruleset) {
            $return[] = $ruleset->listAllRulesets();
        }

        return array_merge(...$return);
    }

    public function getClass($name) {
        // accepted names :
        // PHP full name : Analyzer\\Type\\Class
        // PHP short name : Type\\Class
        // Human short name : Type/Class
        // Human shortcut : Class (must be unique among the classes)

        if (strpos($name, '\\') !== false) {
            if (substr($name, 0, 16) === 'Exakat\\Analyzer\\') {
                $class = $name;
            } else {
                $class = "Exakat\\Analyzer\\$name";
            }
        } elseif (strpos($name, '/') !== false) {
            $class = 'Exakat\\Analyzer\\' . str_replace('/', '\\', $name);
        } elseif (strpos($name, '/') === false) {
            $found = $this->getSuggestionClass($name);

            if (empty($found)) {
                return false; // no class found
            }
            
            if (count($found) > 1) {
                return false;
            }
            
            $class = $found[0];
        } else {
            $class = $name;
        }

        if (!class_exists($class)) {
            return false;
        }

        $actualClassName = new \ReflectionClass($class);
        if ($class === $actualClassName->getName()) {
            return $class;
        } else {
            // problems with the case
            return false;
        }
    }

    public function getSuggestionRulesets(array $ruleset) {
        $list = $this->listAllRulesets();
        
        return array_filter($list, function ($c) use ($rulesets) {
            foreach($rulesets as $r) {
                $l = levenshtein($c, $r);
                if ($l < 8) {
                    return true;
                }
            }
            return false;
        });
    }
    
    public function getSuggestionClass($name) {
        if (empty($this->all)) {
            return array();
        }

        return array_filter($this->listAllAnalyzer(), function ($c) use ($name) {
            $l = levenshtein($c, $name);

            return $l < 8;
        });
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

    public function getAnalyzerInExtension($name) {
        $return = array(array());
        
        foreach($this->all as $ext) {
            $return[] = preg_grep("#/$name\$#", $ext);
        }

        return array_merge(...$return);
    }

}
?>
