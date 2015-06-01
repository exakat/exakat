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

class Json extends \Report\Format { 
    private $output = array();
    private $rule = '';
    protected static $analyzer = null;
    private $summary = null;

    protected $fileExtension ='json';

    public function __construct() {
        parent::__construct();
        
        $this->format = 'Json';
    }
    
    public function render($output, $data) {
        $output->push(" Json for ".get_class($this)."\n");
    }
    
    public function push($key, $value) {
        if ($value == 'Rule') {
            $this->rule = $key;
        } else {
            if (!isset($this->output[$this->rule])) {
                $this->output[$this->rule] = array($key => $value);
            } else {
                $this->output[$this->rule][$key] = $value;
            }
        }
    }
    
    public function toFile($filename) {
        file_put_contents($filename, json_encode($this->output));
        chmod($filename, 0777);
        return ;
    }
    
    public function setAnalyzer($name) {
        \Report\Format\Json::$analyzer = $name;
    }

    public function setSummaryData($data) {
        $this->summary = $data;
    }

    public function setCss() {
        // nothing to do
    }

}

?>
