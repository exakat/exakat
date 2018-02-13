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

namespace Exakat\Tasks\Helpers;

class Intval extends Plugin {
    public $name = 'intval';
    public $type = 'integer';

    static public $PROP_INTVAL      = array('Integer', 'Boolean', 'Real', 'Null', 'Addition');
    
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
                    $actual = (int) $value;
                }
    
                $atom->intval = abs($actual) > PHP_INT_MAX ? 0 : $actual;
                break;

            case 'Real' :
            case 'String' :
                $atom->intval   = (int) trim($atom->code, '"\'');
                break;
    
            case 'Boolean' :
                $atom->intval = (int) (mb_strtolower($atom->code) === 'true');
                break;
    
            case 'Null' :
                $atom->intval = 0;
                break;
    
            case 'Parenthesis' :
                $atom->intval = $extras['CODE']->intval;
                break;
    
            case 'Addition' :
                if ($extras['LEFT']->intval === '')  { break; }
                if ($extras['RIGHT']->intval === '')  { break; }
    
                if ($atom->code === '+') {
                    $atom->intval = $extras['LEFT']->intval + 
                                    $extras['RIGHT']->intval;
                } elseif ($atom->code === '-') {
                    $atom->intval = $extras['LEFT']->intval - $extras['RIGHT']->intval;
                }
                break;

            case 'Multiplication' :
                if ($extras['LEFT']->intval === '')  { break; }
                if ($extras['RIGHT']->intval === '')  { break; }

                if ($atom->code === '*') {
                    $atom->intval = (int) ($extras['LEFT']->intval * $extras['RIGHT']->intval);
                } elseif ($atom->code === '/') {
                    $atom->intval = (int) ($extras['LEFT']->intval / $extras['RIGHT']->intval);
                } elseif ($atom->code === '%') {
                    $atom->intval = (int) ($extras['LEFT']->intval % $extras['RIGHT']->intval);
                }
                break;

            case 'Arrayliteral' : 
                $atom->intval    = (int) (bool) $atom->count;
                break;

            case 'Not' : 
                if ($atom->code === '!') {
                    $atom->intval = !$extras['NOT']->intval;
                } elseif ($atom->code === '~') {
                    print_r($extras);
                    var_dump($extras['NOT']->intval);
                    $atom->intval = ~$extras['NOT']->intval;
                }
                break;

            case 'Logical' : 
                if ($atom->code === '|') {
                    $atom->intval = $extras['LEFT']->intval | $extras['RIGHT']->intval;
                } elseif ($atom->code === '&') {
                    $atom->intval = $extras['LEFT']->intval & $extras['RIGHT']->intval;
                } elseif ($atom->code === '^') {
                    $atom->intval = $extras['LEFT']->intval ^ $extras['RIGHT']->intval;
                } elseif ($atom->code === '&&' || mb_strtolower($atom->code) === 'and') {
                    $atom->intval = $extras['LEFT']->intval && $extras['RIGHT']->intval;
                } elseif ($atom->code === '||' || mb_strtolower($atom->code) === 'or') {
                    $atom->intval = $extras['LEFT']->intval && $extras['RIGHT']->intval;
                } elseif (mb_strtolower($atom->code) === 'xor') {
                    $atom->intval = $extras['LEFT']->intval xor $extras['RIGHT']->intval;
                }
                break;

            case 'Concatenation' : 
                $atom->intval = (int) $atom->noDelimiter;
                break;

            case 'Comparison' : 
                if ($atom->code === '==') {
                    $atom->intval = $extras['LEFT']->intval == $extras['RIGHT']->intval;
                } elseif ($atom->code === '===') {
                    $atom->intval = $extras['LEFT']->intval === $extras['RIGHT']->intval;
                } elseif ($atom->code === '!=' || $atom->code === '<>') {
                    $atom->intval = $extras['LEFT']->intval != $extras['RIGHT']->intval;
                } elseif ($atom->code === '!==') {
                    $atom->intval = $extras['LEFT']->intval !== $extras['RIGHT']->intval;
                } elseif ($atom->code === '>') {
                    $atom->intval = $extras['LEFT']->intval > $extras['RIGHT']->intval;
                } elseif ($atom->code === '<') {
                    $atom->intval = $extras['LEFT']->intval < $extras['RIGHT']->intval;
                } elseif ($atom->code === '>=') {
                    $atom->intval = $extras['LEFT']->intval >= $extras['RIGHT']->intval;
                } elseif ($atom->code === '<=') {
                    $atom->intval = $extras['LEFT']->intval <= $extras['RIGHT']->intval;
                }
                break;

        default :
            
        }
    }
}

?>
