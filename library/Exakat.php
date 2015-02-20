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

class Exakat {
    const VERSION = '1.2.3';
    const BUILD = '22';
    
    public function execute(Config $config) {
        switch ($config->command) {
            case 'doctor' : 
                $doctor = new Tasks\Doctor();
                print $doctor->run($config);
                break;

            case 'init' : 
                $task = new Tasks\Initproject();
                print $task->run($config);
                break;

            case 'files' : 
                $task = new Tasks\Files();
                print $task->run($config);
                break;

            case 'version' : 
            default : 
                $version = self::VERSION;
                $date = date('r', filemtime(__FILE__));
                print "Exakat : @ 2014-2015 Damien Seguy. 
Version : {$version} - $date\n";
                print_r($config);
                break;
        }
    }
}

?>
