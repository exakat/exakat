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


class Log {
    private $name  = null;
    private $log   = null;
    private $begin = 0;
    private $first = '';
    
    public function __construct($name = null, $dir = '.') {
        $this->name = $name;

        if (!file_exists($dir.'/log/'.$this->name.'.log')) { return ; }
        $this->log = fopen($dir.'/log/'.$this->name.'.log', 'w+');
        if (!$this->log) {
            display('Couldn\'t create log in '.$dir.'/log/');
            $this->log = null;
        }

        $this->first = $this->name.' created on '.date('r');
        $this->begin = microtime(true);
    }

    public function __destruct() {
        if ($this->log === null) { return true; }

        $this->log('Duration : '.number_format(1000 * (microtime(true) - $this->begin), 2, '.', ''));
        $this->log($this->name.' closed on '.date('r'));
        
        if ($this->log !== null) {
            fclose($this->log);
            unset($this->log);
        } 
    }
    
    public function log($message) {
        if ($this->log === null) { return true; }

        if ($this->first !== null) { 
            fwrite($this->log, $this->first."\n");
            $this->first = null;
        }
        
        fwrite($this->log, $message."\n");
    }
}

?>
