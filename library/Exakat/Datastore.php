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

namespace Exakat;

use Exakat\Config;
use Exakat\Exakat;
use Exakat\Exceptions\WrongNumberOfColsForAHash;
use Exakat\Exceptions\NoStructureForTable;

class Datastore {
    protected $sqliteRead = null;
    protected $sqliteWrite = null;
    protected $sqlitePath = null;

    const CREATE = 1;
    const REUSE = 2;
    const TIMEOUT_WRITE = 5000;
    const TIMEOUT_READ = 6000;

    public function __construct(Config $config, $create = self::REUSE) {
        $this->sqlitePath = "$config->projects_root/projects/{$config->project}/datastore.sqlite";

        // if project dir isn't created, we are about to create it.
        if (!file_exists("$config->projects_root/projects/{$config->project}")) {
            return;
        }

        if ($create === self::CREATE) {
            if (file_exists($this->sqlitePath)) {
                unlink($this->sqlitePath);
            }
            // force creation
            $this->sqliteWrite = new \Sqlite3($this->sqlitePath, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
            $this->sqliteWrite->close();
            $this->sqliteWrite = null;
        }

        if ($this->sqliteWrite === null) {
            $this->sqliteWrite = new \Sqlite3($this->sqlitePath, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
            $this->sqliteWrite->enableExceptions(true);
            $this->sqliteWrite->busyTimeout(self::TIMEOUT_WRITE);
            // open the read connexion AFTER the write, to have the sqlite databse created
            $this->sqliteRead = new \Sqlite3($this->sqlitePath, \SQLITE3_OPEN_READONLY);
            $this->sqliteRead->enableExceptions(true);
            $this->sqliteRead->busyTimeout(self::TIMEOUT_READ);
        }

        if ($create === self::CREATE) {
            $this->cleanTable('hash');
            $this->addRow('hash', array('exakat_version'       => Exakat::VERSION,
                                        'exakat_build'         => Exakat::BUILD,
                                        'datastore_creation'   => date('r', time()),
                                        'project'              => $config->project,
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
        }
    }

    public function addRow($table, $data) {
        if (empty($data)) {
            return true;
        }

        $this->checkTable($table);

        $first = current($data);
        if (is_array($first)) {
            $cols = array_keys($first);
        } else {
            $query = "PRAGMA table_info($table)";
            $res = $this->sqliteRead->query($query);

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
                    $e = \Sqlite3::escapeString($e);
                }
                unset($e);
            } else {
                $d = array(\Sqlite3::escapeString($key), \Sqlite3::escapeString($row));
            }
            
            $values[] = '('.makeList($d, "'").')';
            
            if (count($values) > 10) {
                $query = "REPLACE INTO $table ($colList) VALUES ".makeList($values, '');
                $this->sqliteWrite->querySingle($query);

                $values = array();
            }
        }

        if (!empty($values)) {
                $query = "REPLACE INTO $table ($colList) VALUES ".makeList($values, '');
            $this->sqliteWrite->querySingle($query);
        }

        return true;
    }

    public function deleteRow($table, $data) {
        if (empty($data)) {
            return true;
        }

        $this->checkTable($table);

        $first = current($data);
        if (is_array($first)) {
            $cols = array_keys($first);
        } else {
            $query = "PRAGMA table_info($table)";
            $res = $this->sqliteRead->query($query);

            $cols = array();
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                if ($row['name'] === 'id') { continue; }
                $cols[] = $row['name'];
            }
        }

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
            $query = "DELETE FROM $table WHERE $col IN (makeList($d))";
            $this->sqliteWrite->querySingle($query);
        }

        return true;
    }

    public function getRow($table) {
        try {
            $query = "SELECT * FROM $table";
            $res = $this->sqliteRead->query($query);
        } catch (\Exception $e) {
            return array();
        }
        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row;
        }

        return $return;
    }

    public function getCol($table, $col) {
        $query = "SELECT $col FROM $table";
        try {
            $res = $this->sqliteRead->query($query);
        } catch (\Exception $e) {
            return array();
        }

        $return = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[] = $row[$col];
        }

        return $return;
    }

    public function getHash($key) {
        $query = 'SELECT value FROM hash WHERE key=:key';
        $stmt = $this->sqliteRead->prepare($query);
        $stmt->bindValue(':key', $key, \SQLITE3_TEXT);
        $res = $stmt->execute();

        if (!$res) {
            return null;
        }

        $row = $res->fetchArray(\SQLITE3_ASSOC);
        return $row['value'];
    }

    public function getAllHash($table) {
        $query = "SELECT key, value FROM $table";
        $stmt = $this->sqliteRead->prepare($query);
        $res = $stmt->execute();

        if (!$res) {
            return array();
        }
        
        $return = array();
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            $return[$row[0]] = (int) $row[1];
        }
        return $return;
    }

    public function getHashAnalyzer($analyzer) {
        $query = 'SELECT key, value FROM hashAnalyzer WHERE analyzer=:analyzer';
        $stmt = $this->sqliteRead->prepare($query);
        $stmt->bindValue(':analyzer', $analyzer, \SQLITE3_TEXT);
        $res = $stmt->execute();

        if (!$res) {
            return array();
        }

        $return = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['key']] = $row['value'];
        }

        return $return;
    }

    public function addRowAnalyzer($analyzer, $key, $value = '') {
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

    public function hasResult($table) {
        $query = "SELECT * FROM $table LIMIT 1";
        $r = $this->sqliteRead->querySingle($query);

        return !empty($r);
    }

    public function cleanTable($table) {
        // Total destroy table
        $query = "DROP TABLE IF EXISTS $table";
        $this->sqliteWrite->querySingle($query);
        $this->checkTable($table);

        return true;
    }

    private function checkTable($table) {
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

    public function reload() {
        $this->sqliteRead->close();
        $this->sqliteWrite->close();

        $this->sqliteWrite = new \Sqlite3($this->sqlitePath, \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
        $this->sqliteWrite->busyTimeout(self::TIMEOUT_WRITE);
        // open the read connexion AFTER the write, to have the sqlite databse created
        $this->sqliteRead = new \Sqlite3($this->sqlitePath, \SQLITE3_OPEN_READONLY);
        $this->sqliteWrite->busyTimeout(self::TIMEOUT_READ);
    }
    
    public function ignoreFile($file, $reason = 'unknown') {
        $this->sqliteWrite->query('DELETE FROM files WHERE file = "'.$this->sqliteWrite->escapeString($file).'"');
        $this->sqliteWrite->query('INSERT INTO ignoredFiles VALUES (NULL, "'.$this->sqliteWrite->escapeString($file).'", "'.$reason.'")');
    }
}

?>
