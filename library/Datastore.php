<?php

class Datastore {
    private $sqlite = null;
    
    public function __construct($project) {
        $this->sqlite = new sqlite3('./projects/'.$project.'/datastore.sqlite');
    }

    public function addRow($table, $data) {
        $this->checkTable($table);
        
        foreach($data as $row) {
            $d = array_values($row);
            foreach($d as $id => $e) {
                $d[$id] = Sqlite3::escapeString($e);
            }
            $query = "INSERT INTO $table (".join(", ", array_keys($row)).") VALUES ('".join("', '", $d)."')";
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
        
        if ($res == 1) {return true; }

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

            default : 
                print "No structure for table $table\n";
                return false;
        }

        $this->sqlite->query($createTable);
        
        return true;
    }
}

?>