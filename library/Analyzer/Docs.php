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
        $query = <<<SQL
        SELECT a.folder, a.name FROM analyzers AS a 
    JOIN analyzers_categories AS ac 
        ON ac.id_analyzer = a.id
    JOIN categories AS c
        ON c.id = ac.id_categories
    WHERE
        c.name = '$theme'
SQL;
        
        $res = $this->sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray()) {
            $return[] = $row['folder'].'/'.$row['name'];
        }
        
        return $return;
    }

    public function getSeverity($analyzer) {
        list($foo, $folder, $name) = explode('\\', $analyzer);
        $query = "SELECT severity FROM analyzers WHERE folder = '$folder' AND name = '$name'";

        $res = $this->sqlite->query($query);
        $res2 = $res->fetchArray();
        if (empty($res2[0])) { print "No Severity for $folder\\$name ( read : '$res2[0]'\n"; }

        $return = constant("\\Analyzer\\Analyzer::$res2[0]");
        
        if (empty($return['severity'])) { print "No Severity for $folder\\$name ( read : '$res2[0]')\n"; }

        return $return;
    }

    public function getTimeToFix($analyzer) {
        list($foo, $folder, $name) = explode('\\', $analyzer);
        $query = "SELECT timetofix FROM analyzers WHERE folder = '$folder' AND name = '$name'";

        $res = $this->sqlite->query($query);
        $res2 = $res->fetchArray();

        $return = constant("\\Analyzer\\Analyzer::$res2[0]");

        if (empty($return['severity'])) { print "No TTF for $folder\\$name ( read : $res2[0]\n"; }

        return $return;
    }
}
?>