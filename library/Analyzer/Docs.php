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


namespace Analyzer;

class Docs {
    private $sqlite = null;
    private $phar_tmp = null;
    
    public function __construct($path) {
        if (substr($path, 0, 4) == 'phar') {
            $this->phar_tmp = tempnam(sys_get_temp_dir(), 'exDocs').'.sqlite';
            copy($path, $this->phar_tmp);
            $path = $this->phar_tmp;
        }
        $this->sqlite = new \Sqlite3($path, SQLITE3_OPEN_READONLY);
    }

    public function __destruct() {
        if ($this->phar_tmp !== null) {
            unlink($this->phar_tmp);
        }
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
        list(, $folder, $name) = explode('\\', $analyzer);
        $query = "SELECT severity FROM analyzers WHERE folder = '$folder' AND name = '$name'";

        $res = $this->sqlite->query($query);
        $res2 = $res->fetchArray();
        if (empty($res2[0])) { 
            print "No Severity for $folder\\$name ( read : '$res2[0]')\n";  
            print_r($res2); 
            die();
        }

        $return = constant("\\Analyzer\\Analyzer::$res2[0]");
        
        if (empty($return)) { 
            print "No Severity for $folder\\$name ( read : '$res2[0]')\n"; 
            var_dump($return); 
            die();
        }

        return $return;
    }

    public function getTimeToFix($analyzer) {
        list(, $folder, $name) = explode('\\', $analyzer);
        $query = "SELECT timetofix FROM analyzers WHERE folder = '$folder' AND name = '$name'";

        $res = $this->sqlite->query($query);
        $res2 = $res->fetchArray();

        $return = constant("\\Analyzer\\Analyzer::$res2[0]");

        if (empty($return['severity'])) { 
            print "No TTF for $folder\\$name ( read : $res2[0]\n"; 
        }

        return $return;
    }

    public function getVendors() {
        $query = <<<SQL
        SELECT vendor FROM vendors
SQL;
        
        $res = $this->sqlite->query($query);

        $return = array();
        while($row = $res->fetchArray()) {
            $return[] = $row['vendor'];
        }
        
        return $return;
    }
}
?>
