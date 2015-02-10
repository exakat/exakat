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


namespace Report\Format;

class Sqlite extends \Report\Format { 
    private $output = array();
    protected static $analyzer = null;
    private $summary = null;

    protected $fileExtension ='sqlite';

    public function __construct() {
        parent::__construct();
        
        $this->format = 'Sqlite';
    }
    
    public function render($output, $data) {
        // Nothing
    }
    
    public function push($render) {
        $this->output[] = $render;
    }
    
    public function toFile($filename) {
        if (file_exists($filename)) {
            unlink($filename);
        }

        $db = new \SQLite3($filename);
        $db->query('CREATE TABLE reports (id INTEGER PRIMARY KEY AUTOINCREMENT, analyzer TEXT, value TEXT, count INT)');

        foreach($this->output as $t) {
            foreach($t as $k => $v) {
                $t[$k] = $db->escapeString($v);
            }
            if (count($t) != 3) {
                print_r($t);
                die(__METHOD__);
            }
            $db->query("INSERT INTO reports (analyzer, value, count) VALUES ('".join("', '", $t)."')");
        }
        
        return true;
    }
    
    public function setAnalyzer($name) {
        \Report\Format\Sqlite::$analyzer = $name;
    }

    public function setSummaryData($data) {
        $this->summary = $data;
    }

    public function setCss() {
        // nothing to do
    }

}

?>
