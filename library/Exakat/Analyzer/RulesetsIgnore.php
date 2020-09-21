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
declare(strict_types = 1);

namespace Exakat\Analyzer;


class RulesetsIgnore implements RulesetsInterface {
    private $ignoreList      = array();

    public function __construct(array $list) {
        // No check on existence : if don't exist, it is already ignored.
        $this->ignoreList      = $list;
    }

    public function getRulesetsAnalyzers(array $rulesets = array()): array {
        return $this->ignoreList;
    }

    public function getRulesetForAnalyzer(string $analyzer = ''): array {
        return array();
    }

    public function getRulesetsForAnalyzer(array $analyzer = array()): array {
        return array();
    }

    public function getSeverities(): array {
        return array();
    }

    public function getTimesToFix(): array {
        return array();
    }

    public function getFrequences(): array {
        return array();
    }

    public function listAllAnalyzer(string $folder = ''): array {
        return array();
    }

    public function listAllRulesets(array $ruleset = array()): array {
        return array();
    }

    public function getClass(string $name): string {
        return '';
    }

    public function getSuggestionRuleset(array $rulesets = array()): array {
        return array();
    }

    public function getSuggestionClass(string $name): array {
        return array();
    }

    public function getAnalyzerInExtension(string $name): array {
        return array();
    }

}
?>
