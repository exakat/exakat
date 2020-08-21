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

namespace Exakat;

use Exakat\Exceptions\WrongNumberOfColsForAHash;
use Exakat\Exceptions\NoStructureForTable;

class Datastore {
    private $sqliteRead  = null;
    private $sqliteWrite = null;
    private $config      = null;

    const CREATE = 1;
    const REUSE = 2;
    const TIMEOUT_WRITE = 5000;
    const TIMEOUT_READ = 6000;

    public function __construct() {
        $this->config = exakat('config');
    }

    public function create(): void {
        if (file_exists($this->config->datastore)) {
            unlink($this->config->datastore);
        }

        if ($this->config->project->isDefault()) {
            die("Could not create datastore for a project without name. Aborting\n");
        }

        // force creation
        $this->sqliteWrite = new \Sqlite3($this->config->datastore, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);

        $this->cleanTable('hash');
        $this->addRow('hash', array('exakat_version'       => Exakat::VERSION,
                                    'exakat_build'         => Exakat::BUILD,
                                    'datastore_creation'   => date('r', time()),
                                    'project'              => $this->config->project,
                                    'project_description'  => $this->config->project_description,
                                    'write_acces'          => 0,
                                    ));

        $this->cleanTable('hashAnalyzer');
        $this->cleanTable('analyzed');
        $this->cleanTable('tokenCounts');
        $this->cleanTable('functioncalls');
        $this->cleanTable('externallibraries');
        $this->cleanTable('ignoredFiles');
        $this->cleanTable('files');
        $this->cleanTable('shortopentag');
        $this->cleanTable('composer');
        $this->cleanTable('configFiles');
        $this->cleanTable('dictionary');
        $this->cleanTable('linediff');

        $this->cleanTable('ignoredCit');
        $this->cleanTable('ignoredFunctions');
        $this->cleanTable('ignoredConstants');

        $this->sqliteWrite->close();
        $this->sqliteWrite = null;

        $this->reuse();
    }

    public function reuse(): void {
        try {
            $this->sqliteWrite = new \Sqlite3($this->config->datastore, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
        } catch(\Throwable $e) {
            return;
        }

        try {
            $this->sqliteWrite->enableExceptions(true);
            $this->sqliteWrite->busyTimeout(self::TIMEOUT_WRITE);

            $this->sqliteWrite->query('UPDATE hash SET value = value + 1 WHERE key IN ("write_access")');
        } catch(\Throwable $e) {
            // ignore
        }

       // open the read connexion AFTER the write, to have the sqlite database created
       $this->sqliteRead = new \Sqlite3($this->config->datastore, \SQLITE3_OPEN_READONLY);
       $this->sqliteRead->enableExceptions(true);
       $this->sqliteRead->busyTimeout(self::TIMEOUT_READ);
    }

    public function addRow(string $table, array $data): bool {
        if (empty($data)) {
            return true;
        }

        $this->checkTable($table);

        $first = current($data);
        if (is_array($first)) {
            $cols = array_keys($first);
        } else {
            $query = "PRAGMA table_info($table)";
            $res = $this->sqliteWrite->query($query);

            $cols = array();
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                if ($row['name'] === 'id') {
                    continue;
                }
                $cols[] = $row['name'];
            }

            if (count($cols) !== 2) {
                throw new WrongNumberOfColsForAHash($table, count($cols));
            }
        }

        $colList = makeList($cols, '');
        $values = array();
        $total = 0;
        foreach($data as $key => $row) {
            ++$total;
            if (is_array($row)) {
                $d = array_values($row);
                foreach($d as &$e) {
                    $e = \Sqlite3::escapeString((string) $e);
                }
                unset($e);
            } else {
                $d = array(\Sqlite3::escapeString((string) $key), \Sqlite3::escapeString((string) $row));
            }

            $values[] = '(' . makeList($d, "'") . ')';

            if (count($values) > 10) {
                $query = "REPLACE INTO $table ($colList) VALUES " . makeList($values, '');
                $this->sqliteWrite->querySingle($query);

                $values = array();
            }
        }

        if (!empty($values)) {
            $query = "REPLACE INTO $table ($colList) VALUES " . makeList($values, '');
            $this->sqliteWrite->querySingle($query);
        }

        return true;
    }

    public function deleteRow(string $table, array $data): bool {
        if (empty($data)) {
            return true;
        }

        $this->checkTable($table);

        foreach($data as $col => $row) {
            if (is_array($row)) {
                $d = array_values($row);
                foreach($d as &$e) {
                    $e = \Sqlite3::escapeString($e);
                }
                unset($e);
            } else {
                $d = array(\Sqlite3::escapeString($row));
            }

            $list = makeList($d);
            $query = "DELETE FROM $table WHERE $col IN (" . makeList($d) . ')';
            $this->sqliteWrite->querySingle($query);
        }

        return true;
    }

    public function getRow(string $table): array {
        try {
            $query = "SELECT * FROM $table";
            $res = $this->sqliteRead->query($query);
        } catch (\Exception $e) {
        }
        $return = array();

        if (isset($res)) {
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $return[] = $row;
            }
        }

        return $return;
    }

    public function getCol(string $table, string $col): array {
        $query = "SELECT $col FROM $table";
        try {
            $res = $this->sqliteRead->query($query);
        } catch (\Throwable $e) {
            // This also catch when the datastore is not available
        }

        $return = array();

        if (isset($res)) {
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $return[] = $row[$col];
            }
        }

        return $return;
    }

    public function getHash(string $key): ?string {
        $query = 'SELECT value FROM hash WHERE key=:key';
        try {
            $stmt = $this->sqliteRead->prepare($query);
        } catch (\Exception $e) {
            $stmt = null;
        }
        if ($stmt === null) {
            return null;
        }
        $stmt->bindValue(':key', $key, \SQLITE3_TEXT);

        $res = $stmt->execute();

        if ($res === false) {
            return null;
        }

        $row = $res->fetchArray(\SQLITE3_ASSOC);
        if (isset($row['value'])) {
            return $row['value'];
        } else {
            return null;
        }
    }

    public function getAllHash(string $table = 'hash'): array {
        $query = "SELECT key, value FROM $table";
        $stmt = $this->sqliteRead->prepare($query);
        $res = $stmt->execute();

        if ($res === false) {
            return array();
        }

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            $return[$row[0]] = (int) $row[1];
        }
        return $return;
    }

    public function getHashAnalyzer(string $analyzer): array {
        $query = 'SELECT key, value FROM hashAnalyzer WHERE analyzer=:analyzer';
        $stmt = $this->sqliteRead->prepare($query);
        $stmt->bindValue(':analyzer', $analyzer, \SQLITE3_TEXT);
        $res = $stmt->execute();

        if ($res === false) {
            return array();
        }

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['key']] = $row['value'];
        }

        return $return;
    }

    public function addRowAnalyzer(string $analyzer, $key, string $value = ''): bool {
        if (is_array($key)) {
            foreach($key as &$v) {
                $v['analyzer'] = $analyzer;
            }
            unset($v);
            return $this->addRow('hashAnalyzer', $key);
        } else {
            return $this->addRow('hashAnalyzer', array('analyzer' => $analyzer,
                                                        $key      => $value));
        }
    }

    public function hasResult(string $table): bool {
        $query = "SELECT * FROM $table LIMIT 1";
        $r = $this->sqliteRead->querySingle($query);

        return !empty($r);
    }

    public function cleanTable(string $table): bool {
        // Total destroy table
        $query = "DROP TABLE IF EXISTS $table";
        $this->sqliteWrite->querySingle($query);
        $this->checkTable($table);

        return true;
    }

    private function checkTable(string $table): bool {
        $res = $this->sqliteWrite->querySingle("SELECT count(*) FROM sqlite_master WHERE name=\"$table\"");

        if ($res === 1) {
            return true;
        }

        switch($table) {
            case 'compilation80' :
            case 'compilation74' :
            case 'compilation73' :
            case 'compilation72' :
            case 'compilation71' :
            case 'compilation70' :
            case 'compilation56' :
            case 'compilation55' :
            case 'compilation54' :
            case 'compilation53' :
            case 'compilation52' :
                $createTable = <<<SQLITE
CREATE TABLE $table (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'shortopentag' :
                $createTable = <<<'SQLITE'
CREATE TABLE shortopentag (
  id INTEGER PRIMARY KEY,
  file TEXT
);
SQLITE;
                break;

            case 'files' :
                $createTable = <<<'SQLITE'
CREATE TABLE files (
  id INTEGER PRIMARY KEY,
  file TEXT,
  fnv132 TEXT,
  modifications INTEGER
);
SQLITE;
                break;

            case 'ignoredFiles' :
                $createTable = <<<'SQLITE'
CREATE TABLE ignoredFiles (
  id INTEGER PRIMARY KEY,
  file TEXT,
  reason INTEGER DEFAULT 1
);
SQLITE;
                break;

            case 'hash' :
                $createTable = <<<'SQLITE'
CREATE TABLE hash (
  id INTEGER PRIMARY KEY,
  key TEXT UNIQUE,
  value TEXT
);
SQLITE;
                break;

            case 'hashAnalyzer' :
                $createTable = <<<'SQLITE'
CREATE TABLE hashAnalyzer (
  id INTEGER PRIMARY KEY,
  analyzer TEXT,
  key TEXT UNIQUE,
  value TEXT
);
SQLITE;
                break;

            case 'analyzed' :
                $createTable = <<<'SQLITE'
CREATE TABLE analyzed (
  id INTEGER PRIMARY KEY,
  analyzer TEXT UNIQUE,
  counts TEXT
);
SQLITE;
                break;

            case 'tokenCounts' :
                $createTable = <<<'SQLITE'
CREATE TABLE tokenCounts (
  id INTEGER PRIMARY KEY,
  token TEXT UNIQUE,
  counts INTEGER
);
SQLITE;
                break;

            case 'functioncalls' :
                $createTable = <<<'SQLITE'
CREATE TABLE functioncalls (
  id INTEGER PRIMARY KEY,
  functioncall TEXT UNIQUE,
  counts INTEGER
);
SQLITE;
                break;

            case 'externallibraries' :
                $createTable = <<<'SQLITE'
CREATE TABLE externallibraries (
  id INTEGER PRIMARY KEY,
  library TEXT UNIQUE,
  file TEXT
);
SQLITE;
                break;

            case 'composer' :
                $createTable = <<<'SQLITE'
CREATE TABLE composer (
  id INTEGER PRIMARY KEY,
  component TEXT UNIQUE,
  version TEXT
);
SQLITE;
                break;

            case 'configFiles' :
                $createTable = <<<'SQLITE'
CREATE TABLE configFiles (
  id INTEGER PRIMARY KEY,
  file TEXT UNIQUE,
  name TEXT UNIQUE,
  homepage TEXT UNIQUE
);
SQLITE;
                break;

            case 'dictionary' :
                $createTable = <<<'SQLITE'
CREATE TABLE dictionary (
  id INTEGER PRIMARY KEY,
  key TEXT UNIQUE,
  value TEXT
);
SQLITE;
                break;

            case 'linediff' :
                $createTable = <<<'SQLITE'
CREATE TABLE linediff (
  id INTEGER PRIMARY KEY,
  file TEXT UNIQUE,
  line INTEGER,
  diff INTEGER
);
SQLITE;
                break;

            case 'ignoredCit' :
                $createTable = <<<'SQLITE'
CREATE TABLE ignoredCit (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                           name TEXT,
                           fullnspath TEXT,
                           fullcode TEXT,
                           type TEXT
                )
SQLITE;
                break;

            case 'ignoredFunctions' :
                $createTable = <<<'SQLITE'
CREATE TABLE ignoredFunctions (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                 name TEXT,
                                 fullnspath TEXT,
                                 fullcode TEXT
                )
SQLITE;
                break;

            case 'ignoredConstants' :
                $createTable = <<<'SQLITE'
CREATE TABLE ignoredConstants (  id INTEGER PRIMARY KEY AUTOINCREMENT,
                                 name TEXT,
                                 fullnspath TEXT,
                                 fullcode TEXT,
                                 value TEXT
                )
SQLITE;
                break;

            default :
                throw new NoStructureForTable($table);
        }

        $this->sqliteWrite->query($createTable);

        return true;
    }

    public function reload(): void {
        $this->sqliteRead->close();
        $this->sqliteWrite->close();

        $this->sqliteWrite = new \Sqlite3($this->config->datastore, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
        $this->sqliteWrite->busyTimeout(self::TIMEOUT_WRITE);
        // open the read connexion AFTER the write, to have the sqlite databse created
        $this->sqliteRead = new \Sqlite3($this->config->datastore, \SQLITE3_OPEN_READONLY);
        $this->sqliteWrite->busyTimeout(self::TIMEOUT_READ);
    }

    public function ignoreFile(string $file, string $reason = 'unknown'): void {
        $this->sqliteWrite->query('DELETE FROM files WHERE file = \'' . $this->sqliteWrite->escapeString($file) . '\'');
        $this->sqliteWrite->query('INSERT INTO ignoredFiles VALUES (NULL, \'' . $this->sqliteWrite->escapeString($file) . '\', "' . $reason . '")');
    }

    public function storeQueries(array $queries): int {
        $this->sqliteWrite->lastErrorCode();
        foreach($queries as $query) {
            $res = $this->sqliteWrite->query($query);
            if ($this->sqliteWrite->lastErrorCode()) {
                print  $query . PHP_EOL . PHP_EOL;
            }
        }

        return count($queries);
    }

}

?>
