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


namespace Exakat\Analyzer;

use Exakat\Autoload\Autoloader;

class RulesetsDev {
    private $all           = array('All' => array());
    private $rulesets      = array();

    public function __construct(Autoloader $dev) {
        $this->all      = $dev->getAllAnalyzers() ?: array('All' => array());
        $this->rulesets = array_keys($this->all);
    }

    public function getSuggestionRuleset(array $rulesets = array()): array {
        return array_filter($this->rulesets, function (string $c) use ($rulesets): bool {
            foreach($rulesets as $r) {
                $l = levenshtein($c, $r);
                if ($l < 8) {
                    return true;
                }
            }
            return false;
        });
    }

    public function listAllAnalyzer(string $folder = ''): array {
        if (empty($this->all)) {
            return array();
        }

        $return = array_merge(...array_values($this->all));
        if (empty($folder)) {
            return $return;
        }

        return preg_grep("#$folder/#", $return);
    }

    public function listAllRulesets(array $ruleset = null): array {
        return $this->rulesets;
    }

    public function getRulesetsAnalyzers(array $ruleset = null): array {
        if (empty($ruleset)) {
            return array();
        }
        $return = array();
        foreach($ruleset as $t) {
            $return[] = $this->all[$t] ?? array();
        }

        return array_merge(...$return);
    }

    public function getAnalyzerInExtension(string $name): array {
        if (!isset($this->all['All'])) {
            return array();
        }
        return preg_grep("#/$name\$#", $this->all['All']);
    }

    public function getRulesetsForAnalyzer(array $analyzer = array()): array {
        $return = array();

        if (empty($analyzer)) {
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

    public function getSuggestionClass(string $name): array {
        return array_filter($this->listAllAnalyzer(), function ($c) use ($name) {
            $l = levenshtein($c, $name);

            return $l < 8;
        });
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

        if ($class === null) {
            return false;
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

    public function getSeverities(): array {
        return array_fill_keys($this->all['All'], Analyzer::S_NONE);
    }

    public function getTimesToFix(): array {
        return array_fill_keys($this->all['All'], Analyzer::T_NONE);
    }
}
?>
