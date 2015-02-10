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


error_reporting(E_ALL);
ini_set('display_errors', 1);

class Autoload {
    static public function autoload_library($name) {
        $path = __DIR__;
        
        $file = $path.'/'.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';
        
        if (file_exists($file)) {
            include($file);
        } else {
            print "Couldn't load class $name in $file\n";
        }
    }

    static public function autoload_test($name) {
        $path = dirname(__DIR__);
        
        $file = $path.'/tests/analyzer/'.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';
        
        if (file_exists($file)) {
            include($file);
        } 

        $file = $path.'/tests/tokenizer/'.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';
        
        if (file_exists($file)) {
            include($file);
        } 
    }

    static public function autoload_phpunit($name) {
        $file = str_replace('_', DIRECTORY_SEPARATOR, $name).'.php';
        $file = str_replace('Test\\', '', $file);
        if (file_exists($file)) {
            include($file);
        }
    }
}

?>
