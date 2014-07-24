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
}

?>