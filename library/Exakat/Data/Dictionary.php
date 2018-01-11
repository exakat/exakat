<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Data;

use Exakat\Datastore;

class Dictionary {
    private $dictionary = array();
    
    public function __construct($datastore) {
        $this->dictionary = $datastore->getAllHash('dictionary');
    }

    public function translate($code) {
        $return = array();

        foreach($code as $c) {
            if (isset($this->dictionary[$c])) {
                $return[] = $this->dictionary[$c];
            }
        }
        
        return $return;
    }
    
    public function grep($regex) {
        $keys = preg_grep($regex, array_keys($this->dictionary));
        
        $return = array();
        foreach($keys as $k) {
            $return[] = $this->dictionary[$k];
        }
        
        return $return;
    }

}
