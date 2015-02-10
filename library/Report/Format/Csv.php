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

class Csv extends \Report\Format { 
    private $output = array();
    private $summary = null;
    protected static $analyzer = null;
    
    protected $fileExtension ='csv';

    public function __construct() {
        parent::__construct();
        
        $this->format = 'Csv';
    }
    
    public function render($output, $data) {
        $output->push(array(" Text for ".get_class($this).""));
    }
    
    public function push($render) {
        $this->output[] = $render;
    }
    
    public function toFile($filename) {
        $fp = fopen($filename, 'w+');
        fputcsv($fp, array('code', 'file', 'row', 'description'));
        foreach($this->output as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
        
        return true;
    }
    
    public function setAnalyzer($name) {
        \Report\Format\Csv::$analyzer = $name;
    }

    public function setSummaryData($data) {
        $this->summary = $data;
    }

    public function setCss() {
        // nothing to do
    }
}

?>
