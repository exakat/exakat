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


class Thread {
    private $pipes = array();
    private $process = array();
    
    public function run($command) {
        $descriptors = array( 0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                              1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                              2 => array('file', "/tmp/error-output.txt", 'a') // stderr is a file to write to
                            );
        $this->process[] = proc_open($command.' &', $descriptors, $pipes);
        // only keeping the read pipe
        $this->pipes[] = $pipes[0];
    }
    
    public function areAllFinished() {
        $w = null;
        $e = null;
        $pipes = $this->pipes;
        $n = stream_select($pipes, $w, $e, 0);
        
        if ($n > 0) {
            foreach($pipes as $id => $pipe) {
                $status = proc_get_status($this->process[$id]);
                if ($status['running'] === false) {
                    unset($this->process[$id]);
                    unset($this->pipes[$id]);
                }
            }
        }
        
        return count($this->process);
    }

    public function waitForAll() {
        if (!$this->areAllFinished()) {
            while($this->areAllFinished()) {
                sleep(rand(0.5, 1.5));
            }
        }
        
        return true;
    }
}

?>
