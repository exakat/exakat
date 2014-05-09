<?php

namespace Analyzer;

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Index\NodeIndex;

class Docs {
    private $sqlite = null;
    
    public function __construct($path) {
        $this->sqlite = new \Sqlite3($path);
    }
    
    public function getThemeAnalyzers($theme) {
        $query = "SELECT a.folder, a.name FROM analyzers AS a 
    JOIN analyzers_categories AS ac 
        ON ac.id_analyzer = a.id
    JOIN categories AS c
        ON c.id = ac.id_categories
    WHERE
        c.name = '$theme'";
        
        $res = $this->sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray()) {
            $return[] = $row['folder'].'/'.$row['name'];
        }
        
        return $return;
    }

}


?>