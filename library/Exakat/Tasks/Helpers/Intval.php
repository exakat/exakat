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

namespace Exakat\Tasks\Helpers;

class Intval extends Plugin {
    public $name = 'intval';
    public $type = 'integer';

    static public $PROP_INTVAL      = array('Integer', 'Boolean', 'Real', 'Null');
    
    public function run($atom, $extras) {
        switch ($atom->atom) {
            case 'Integer' :
                $value = $atom->code;

                if (strtolower(substr($value, 0, 2)) === '0b') {
                    $actual = bindec(substr($value, 2));
                } elseif (strtolower(substr($value, 0, 2)) === '0x') {
                    $actual = hexdec(substr($value, 2));
                } elseif (strtolower($value[0]) === '0') {
                    // PHP 7 will just stop.
                    // PHP 5 will work until it fails
                    $actual = octdec(substr($value, 1));
                } else {
                    $actual = $value;
                }
    
                $atom->intval = abs($actual) > PHP_INT_MAX ? 0 : $actual;

                break;
        case 'Real' :
            $atom->intval   = (int) $atom->code;

            break;

        default :
            
        }
    }
}

?>
