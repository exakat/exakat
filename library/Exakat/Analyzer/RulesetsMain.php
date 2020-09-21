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


class RulesetsMain implements RulesetsInterface {
    private static $sqlite = null;
    private $phar_tmp      = null;

    public function __construct(string $path) {
        if (substr($path, 0, 4) == 'phar') {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), 'exDocs') . '.sqlite';
            copy($path, $this->phar_tmp);
            $docPath = $this->phar_tmp;
        } else {
            $docPath = $path;
        }
        self::$sqlite = new \SQLite3($docPath, \SQLITE3_OPEN_READONLY);
    }

    public function __destruct() {
        if ($this->phar_tmp !== null && file_exists($this->phar_tmp)) {
            unlink($this->phar_tmp);
        }
    }

    public function getRulesetsAnalyzers(array $rulesets = array()): array {
        // Main installation
        if (empty($rulesets)) {
            // Default is ALL of ruleset
            $where = 'WHERE a.folder != "Common" ';
        } else {
            $rulesets = array_map(function (string $x): string { return trim($x, '"'); }, $rulesets);
            $where = 'WHERE a.folder != "Common" AND c.name in (' . makeList($rulesets) . ')';
        }

        $query = <<<SQL
SELECT DISTINCT a.folder, a.name FROM analyzers AS a
    JOIN analyzers_categories AS ac
        ON ac.id_analyzer = a.id
    JOIN categories AS c
        ON c.id = ac.id_categories
    $where
SQL;
        $res = self::$sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = "$row[folder]/$row[name]";
        }

        return $return;
    }

    public function getRulesetForAnalyzer(string $analyzer = ''): array {
        list($vendor, $class) = explode('/', $analyzer);

        $query = <<<SQL
SELECT c.name FROM categories AS c
    JOIN analyzers_categories AS ac
        ON ac.id_categories = c.id
    JOIN analyzers AS a
        ON a.id = ac.id_analyzer
    WHERE
        a.folder = '$vendor' AND
        a.name   = '$class'
SQL;
        $res = self::$sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row['name'];
        }

        return $return;
    }

    public function getRulesetsForAnalyzer(array $analyzer = array()): array {
        if (empty($analyzer)) {
            $where = '';
        } elseif (is_array($analyzer)) {
            $where = ' WHERE c.name IN (' . makeList($analyzer) . ') ';
        }

        $query = <<<SQL
SELECT folder||'/'||a.name AS analyzer, GROUP_CONCAT(c.name) AS categories FROM categories AS c
    JOIN analyzers_categories AS ac
        ON ac.id_categories = c.id
    JOIN analyzers AS a
        ON a.id = ac.id_analyzer
    $where
    GROUP BY analyzer
SQL;
        $res = self::$sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['analyzer']] = explode(',', $row['categories']);
        }

        return $return;
    }

    public function getSeverities(): array {
        $query = "SELECT folder||'/'||name AS analyzer, severity FROM analyzers";

        $return = array();
        $res = self::$sqlite->query($query);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['analyzer']] = empty($row['severity']) ? Analyzer::S_NONE : constant(Analyzer::class . '::' . $row['severity']);
        }

        return $return;
    }

    public function getTimesToFix(): array {
        $query = "SELECT folder||'/'||name AS analyzer, timetofix FROM analyzers";

        $return = array();
        $res = self::$sqlite->query($query);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['analyzer']] = empty($row['timetofix']) ? Analyzer::S_NONE : constant(Analyzer::class . '::' . $row['timetofix']);
        }

        return $return;
    }

    public function getFrequences(): array {
        $query = "SELECT analyzers.folder||'/'||analyzers.name AS analyzer, frequence / 100 AS frequence 
            FROM  analyzers
            LEFT JOIN analyzers_popularity 
                ON analyzers_popularity.id = analyzers.id";

        $return = array();
        $res = self::$sqlite->query($query);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['analyzer']] = empty($row['frequence']) ? 0 : $row['frequence'];
        }

        return $return;
    }

    public function listAllAnalyzer(string $folder = ''): array {
        $query = <<<'SQL'
SELECT folder || '\\' || name AS name FROM analyzers

SQL;
        if (empty($folder)) {
            $stmt = self::$sqlite->prepare($query);
        } else {
            $query .= ' WHERE folder=:folder';
            $stmt = self::$sqlite->prepare($query);

            $stmt->bindValue(':folder', $folder, \SQLITE3_TEXT);
        }
        $res = $stmt->execute();

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = str_replace('\\\\', '\\', $row['name']);
        }

        return $return;
    }

    public function listAllRulesets(array $ruleset = array()): array {
        $query = <<<'SQL'
SELECT name AS name FROM categories

SQL;
        $stmt = self::$sqlite->prepare($query);
        $res = $stmt->execute();

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row['name'];
        }

        return $return;
    }

    public function getClass(string $name): string {
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
                return ''; // no class found
            }

            if (count($found) > 1) {
                return '';
            }

            $class = array_pop($found);
        } else {
            $class = $name;
        }

        if (!class_exists($class)) {
            return '';
        }

        $actualClassName = new \ReflectionClass($class);
        if (strtolower($class) === strtolower($actualClassName->getName())) {
            return $actualClassName->getName();
        } else {
            // problems with the case
            return '';
        }
    }

    public function getSuggestionRuleset(array $rulesets = array()): array {
        $list = $this->listAllRulesets();

        return array_filter($list, function ($c) use ($rulesets) {
            foreach($rulesets as $ruleset) {
                $l = levenshtein($c, $ruleset);
                if ($l < 8) {
                    return true;
                }
            }
            return false;
        });
    }

    public function getSuggestionClass(string $name): array {
        return array_filter($this->listAllAnalyzer(), function ($c) use ($name) {
            $l = levenshtein($c, $name);

            return $l < 8;
        });
    }

    public function getAnalyzerInExtension(string $name): array {
        return array();
    }

}
?>
