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


namespace Exakat\Query\DSL;

class Command {
    static private $id = 0;
    public $gremlin = '';
    public $arguments = array();
    
    function __construct($command, $args = array()) {
        $c = substr_count($command, '***');
        
        $arguments = array();
        for($i = 0; $i < $c; $i++) {
            ++self::$id;
            $arguments['arg'.self::$id] = $args[0];
        }
        $command = str_replace(array_fill(0, $c, '***'), array_keys($arguments), $command);
        
        $this->gremlin = $command;
        $this->arguments = $arguments;
    }
    
    function add(Command $other) {
        $this->gremlin .= ".$other->gremlin";
        $this->arguments += $other->arguments;
    }
}
?>
