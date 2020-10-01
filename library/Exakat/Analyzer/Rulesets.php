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

class Rulesets implements RulesetsInterface {
    private $main   = null;
    private $extra  = null;
    private $dev    = null;
    private $ignore = null;

    private static $instanciated = array();

    public function __construct($path, Autoloader $dev, array $extra_rulesets = array(), array $ignore_rulesets = array()) {
        $this->main   = new RulesetsMain($path);
        $this->extra  = new RulesetsExtra($extra_rulesets);
        $this->dev    = new RulesetsDev($dev);
        $this->ignore = new RulesetsIgnore($ignore_rulesets);
    }

    public function __destruct() {
        $this->main   = null;
        $this->extra  = null;
        $this->dev    = null;
        $this->ignore = null;
    }

    public function getRulesetsAnalyzers(array $rulesets = array()): array {
        $main     = $this->main   ->getRulesetsAnalyzers($rulesets);
        $extra    = $this->extra  ->getRulesetsAnalyzers($rulesets);
        $dev      = $this->dev    ->getRulesetsAnalyzers($rulesets);
        $ignore   = $this->ignore ->getRulesetsAnalyzers($rulesets);

        return array_udiff(array_merge($main, $extra, $dev), $ignore, 'strcasecmp');
    }

    public function getRulesetForAnalyzer(string $analyzer = ''): array {
        $main = $this->main  ->getRulesetForAnalyzer($analyzer);
        $extra = $this->extra->getRulesetForAnalyzer($analyzer);
        $dev   = $this->dev  ->getRulesetForAnalyzer($analyzer);

        return array_merge($main, $extra, $dev);
    }

    public function getRulesetsForAnalyzer(array $analyzer = array()): array {
        $main  = $this->main ->getRulesetsForAnalyzer($analyzer);
        $extra = $this->extra->getRulesetsForAnalyzer($analyzer);
        $dev   = $this->dev  ->getRulesetsForAnalyzer($analyzer);

        return array_merge($main, $extra, $dev);
    }

    public function getSeverities(): array {
        $main  = $this->main ->getSeverities();
        $extra = $this->extra->getSeverities();
        $dev   = $this->dev  ->getSeverities();

        return array_merge($main, $extra, $dev);
    }

    public function getTimesToFix(): array {
        $main  = $this->main ->getTimesToFix();
        $extra = $this->extra->getTimesToFix();
        $dev   = $this->dev  ->getTimesToFix();

        return array_merge($main, $extra, $dev);
    }

    public function getFrequences(): array {
        $main = $this->main->getFrequences();

        return array_merge($main);
    }

    public function listAllAnalyzer(string $folder = ''): array {
        $main  = $this->main ->listAllAnalyzer($folder);
        $extra = $this->extra->listAllAnalyzer($folder);
        $dev   = $this->dev  ->listAllAnalyzer($folder);

        return array_merge($main, $extra, $dev);
    }

    public function listAllRulesets(array $ruleset = array()): array {
        $main  = $this->main ->listAllRulesets($ruleset);
        $extra = $this->extra->listAllRulesets($ruleset);
        $dev   = $this->dev  ->listAllRulesets($ruleset);

        return array_merge($main, $extra, $dev);
    }

    public function getClass(string $name): string {
        if ($class = $this->main->getClass($name)) {
            return $class;
        }

        if ($class = $this->extra->getClass($name)) {
            return $class;
        }

        if ($class = $this->dev->getClass($name)) {
            return $class;
        }

        return '';
    }

    public function getSuggestionRuleset(array $rulesets = array()): array {
        $main  = $this->main ->getSuggestionRuleset($rulesets);
        $extra = $this->extra->getSuggestionRuleset($rulesets);
        $dev   = $this->dev  ->getSuggestionRuleset($rulesets);

        return array_merge($main, $extra, $dev);
    }

    public function getSuggestionClass(string $name): array {
        $main  = $this->main ->getSuggestionClass($name);
        $extra = $this->extra->getSuggestionClass($name);
        $dev   = $this->dev  ->getSuggestionClass($name);

        return array_merge($main, $extra, $dev);
    }

    public function getAnalyzerInExtension(string $name): array {
        $main  = $this->main ->getAnalyzerInExtension($name);
        $extra = $this->extra->getAnalyzerInExtension($name);
        $dev   = $this->dev  ->getAnalyzerInExtension($name);

        return array_merge($main, $extra, $dev);
    }

    public static function resetCache(): void {
        self::$instanciated = array();
    }

    public function getInstance(string $name) {
        if ($analyzer = $this->getClass($name)) {
            if (!isset(self::$instanciated[$analyzer])) {
                self::$instanciated[$analyzer] = new $analyzer();
            }
            return self::$instanciated[$analyzer];
        } else {
            return null;
        }
    }

}
?>
