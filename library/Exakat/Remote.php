<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat;

class Remote {
    public function __construct($url) {
        $this->bits = parse_url($url);
    }

    public function send($json) {
        if ($this->bits['scheme'] === 'file') {
            return $this->sendWithPipe($json);
        } elseif ($this->bits['scheme'] === 'http') {
            return $this->sendWithHTTP($json);
        } else {
            // Throw error
        }
    }
    
    private function sendWithPipe($json) {
        $queuePipe = fopen($this->bits['path'], 'w');
        fwrite($queuePipe, $json.PHP_EOL);
        fclose($queuePipe);
    }

    private function sendWithHTTP($json) {
        $URLload = 'http://'.$this->bits['host'].':'.$this->bits['port'].'/?json='.$json;
        $html = file_get_contents($URLload);
        
        return $html;
    }

}

?>
