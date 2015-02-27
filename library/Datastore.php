<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


class Datastore {
    private $sqlite = null;
    
    public function __construct(Config $config) {
        $this->sqlite = new sqlite3($config->projects_root.'/projects/'.$config->project.'/datastore.sqlite');
    }

    public function addRow($table, $data) {
        $this->checkTable($table);
        
        if (empty($data)) {
            return true;
        }
        
        $first = current($data);
        if (is_array($first)) {
            $cols = array_keys($first);
        } else {
            $query = "PRAGMA table_info($table)";
            $res = $this->sqlite->query($query);
            
            $cols = array();
            while($row = $res->fetchArray()) {
                if ($row['name'] == 'id') { continue; }
                $cols[] = $row['name'];
            }
            
            if (count($cols) != 2) {
                throw new Exceptions\WrongNumberOfColsForAHash();
            }
        }
        
        foreach($data as $key => $row) {
            if (is_array($row)) {
                $d = array_values($row);
                foreach($d as $id => $e) {
                    $d[$id] = Sqlite3::escapeString($e);
                }
                
            } else {
                $d = array($key, $row);
            }

            $query = "REPLACE INTO $table (".join(", ", $cols).") VALUES ('".join("', '", $d)."')";
            $this->sqlite->querySingle($query);
        }
        
        return true;
    }

    public function getRow($table) {
        $query = "SELECT * FROM $table";
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row;
        }
        
        return $return;
    }

    public function getCol($table, $col) {
        $query = "SELECT $col FROM $table";
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row[$col];
        }
        
        return $return;
    }

    public function getHash($key) {
        $query = "SELECT value FROM hash WHERE key=:key";

        $stmt = $this->sqlite->prepare($query);
        $stmt->bindValue(':key', $key, SQLITE3_TEXT);
        $res = $stmt->execute();

        if (!$res) { 
            return null;
        } else {
            return $res->fetchArray(SQLITE3_ASSOC)['value'];
        }
    }

    public function hasResult($table) {
        $query = "SELECT * FROM $table LIMIT 1";
        $r = $this->sqlite->querySingle($query);

        return !empty($r);
    }

    public function cleanTable($table) {
        $this->checkTable($table);
        
        $query = "DELETE FROM $table";
        $this->sqlite->querySingle($query);

        return true;
    }

    private function checkTable($table) {
        $res = $this->sqlite->querySingle('SELECT count(*) FROM sqlite_master WHERE name="'.$table.'"');
        
        if ($res == 1) { return true; }

        switch($table) {
           case 'compilation52' : 
                $createTable = <<<SQLITE
CREATE TABLE compilation52 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation53' : 
                $createTable = <<<SQLITE
CREATE TABLE compilation53 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation54' : 
                $createTable = <<<SQLITE
CREATE TABLE compilation54 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation55' : 
                $createTable = <<<SQLITE
CREATE TABLE compilation55 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation56' : 
                $createTable = <<<SQLITE
CREATE TABLE compilation56 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'compilation70' : 
                $createTable = <<<SQLITE
CREATE TABLE compilation70 (
  id INTEGER PRIMARY KEY,
  file TEXT,
  error TEXT,
  line id
);
SQLITE;
                break;

            case 'shortopentag' : 
                $createTable = <<<SQLITE
CREATE TABLE shortopentag (
  id INTEGER PRIMARY KEY,
  file TEXT
);
SQLITE;
                break;

            case 'files' : 
                $createTable = <<<SQLITE
CREATE TABLE files (
  id INTEGER PRIMARY KEY,
  file TEXT
);
SQLITE;
                break;

            case 'hash' : 
                $createTable = <<<SQLITE
CREATE TABLE hash (
  id INTEGER PRIMARY KEY,
  key TEXT UNIQUE,
  value TEXT
);
SQLITE;
                break;

            default : 
                throw new Exceptions\NoStructureForTable($table);
                return false;
        }

        $this->sqlite->query($createTable);
        
        return true;
    }
}

?>
