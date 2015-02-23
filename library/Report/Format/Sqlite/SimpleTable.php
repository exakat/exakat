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


namespace Report\Format\Sqlite;

class SimpleTable extends \Report\Format\Sqlite { 
    public function render($output, $data) {
        foreach($data as $key => $value) {
            if (count($value) == 1) {
                $array = array('analyzer' => $value[0],
                               'value'    => '',
                               'count'    => 1);
            } elseif (count($value) == 2) {
                $array = array('analyzer' => $value[0],
                               'value'    => $value[1],
                               'count'    => 1);
            } else {
                print_r($value);
                print __METHOD__."\n";
            }
        
            $output->push($array);
        }
    }

}

?>