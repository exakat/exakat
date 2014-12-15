<?php

namespace Data;

class Methods {
    private $sqlite = null;
    
    public function __construct() {
        $this->sqlite = new \sqlite3('./data/methods.sqlite');
    }

    public function getMethodsArgsInterval() {
        $query = "SELECT class, name, args_min, args_max FROM methods";
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row;
        }
        
        return $return;
    }

    public function getFunctionsArgsInterval() {
        $query = "SELECT class, name, args_min, args_max FROM methods WHERE Class = 'PHP'";
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row;
        }
        
        return $return;
    }

    public function getFunctionsReferenceArgs() {
        $query = "SELECT name AS function, 0 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg0 = 'reference' UNION
                  SELECT name AS function, 1 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg1 = 'reference' UNION
                  SELECT name AS function, 2 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg2 = 'reference' UNION
                  SELECT name AS function, 3 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg3 = 'reference' UNION
                  SELECT name AS function, 4 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg4 = 'reference' UNION
                  SELECT name AS function, 5 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg5 = 'reference'
                  ";
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row;
        }
        
        return $return;
    }

    public function getFunctionsValueArgs() {
        $query = "SELECT name AS function, 0 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg0 = 'value' UNION
                  SELECT name AS function, 1 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg1 = 'value' UNION
                  SELECT name AS function, 2 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg2 = 'value' UNION
                  SELECT name AS function, 3 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg3 = 'value' UNION
                  SELECT name AS function, 4 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg4 = 'value' UNION
                  SELECT name AS function, 5 AS position FROM args_is_ref WHERE Class = 'PHP' AND arg5 = 'value'
                  ";
        $res = $this->sqlite->query($query);
        $return = array();
        
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $return[] = $row;
        }
        
        return $return;
    }
}

?>
