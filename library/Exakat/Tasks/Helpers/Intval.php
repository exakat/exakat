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

    static public $PROP_INTVAL      = array('Integer', 'Boolean', 'Real', 'Null', 'Addition', 'String');
    
    public function run($atom, $extras) {
        foreach($extras as $extra) {
            if ($extra->intval === '')  { 
                return ; 
            }
        }

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
                } elseif ($value[0] === '+' || $value[0] === '-') {
                    $actual = (int) pow(-1, substr_count($value, '-')) * (int) strtr($value, '+-', '  ');
                } else {
                    $actual = (int) $value;
                }
    
                $atom->intval = abs($actual) > PHP_INT_MAX ? 0 : $actual;
                break;

            case 'Real' :
            case 'String' :
                $atom->intval   = (int) trimOnce($atom->code);
                break;
    
            case 'Boolean' :
                $atom->intval = (int) (mb_strtolower($atom->code) === 'true');
                break;
    
            case 'Null' :
            case 'Void' :
                $atom->intval = 0;
                break;
    
            case 'Parenthesis' :
                $atom->intval = $extras['CODE']->intval;
                break;
    
            case 'Addition' :
                if ($atom->code === '+') {
                    $atom->intval = $extras['LEFT']->intval + 
                                    $extras['RIGHT']->intval;
                } elseif ($atom->code === '-') {
                    $atom->intval = $extras['LEFT']->intval - $extras['RIGHT']->intval;
                }
                break;

            case 'Multiplication' :
                if ($atom->code === '*') {
                    $atom->intval = (int) ($extras['LEFT']->intval * $extras['RIGHT']->intval);
                } elseif ($atom->code === '/' && $extras['RIGHT']->intval != 0) {
                    $atom->intval = (int) ($extras['LEFT']->intval / $extras['RIGHT']->intval);
                } elseif ($atom->code === '%' && $extras['RIGHT']->intval != 0) {
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
                } elseif ($atom->code === '<=>') {
                    $atom->intval = $extras['LEFT']->intval <=> $extras['RIGHT']->intval;
                }
                break;

            case 'Concatenation' : 
                $intval = array_column($extras, 'intval');
                $atom->intval = (int) implode('', $intval);
                break;

            case 'Ternary' : 
                if ($extras['CONDITION']->intval) {
                    $atom->intval = (int) $extras['THEN']->intval;
                } else {
                    $atom->intval = (int) $extras['ELSE']->intval;
                }
                break;

            case 'Coalesce' : 
                if ($extras['LEFT']->intval) {
                    $atom->intval = (int) $extras['LEFT']->intval;
                } else {
                    $atom->intval = (int) $extras['RIGHT']->intval;
                }
                break;

            case 'Bitshift' : 
                if ($atom->code === '>>') {
                    $atom->intval = $extras['LEFT']->intval >> $extras['RIGHT']->intval;
                } elseif ($atom->code === '<<') {
                    $atom->intval = $extras['LEFT']->intval << $extras['RIGHT']->intval;
                }
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
