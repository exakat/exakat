<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

class Strval extends Plugin {
    public $name = 'noDelimiter';
    public $type = 'string';

    public function run($atom, $extras) {
        foreach($extras as $extra) {
            if ($extra->noDelimiter === null)  {
                $atom->noDelimiter = null;
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
                    $actual = (string) (int) pow(-1, substr_count($value, '-')) * (int) strtr($value, '+-', '  ');
                } else {
                    $actual = (string) (int) $value;
                }
    
                $atom->noDelimiter = (string) (abs($actual) > PHP_INT_MAX ? 0 : $actual);
                break;

            case 'Float' :
            case 'String' :
                if (empty($extra)) {
                    $atom->noDelimiter = (string) trimOnce($atom->code);
                } else {
                    $noDelimiters = array_column($extras, 'noDelimiter');
                    $atom->noDelimiter = implode('', $noDelimiters);
                }
                break;

            case 'Constant' :
                $atom->noDelimiter = $extras['VALUE']->noDelimiter;
                break;

            case 'Boolean' :
                $atom->noDelimiter = (string) (mb_strtolower($atom->code) === 'true');
                break;
    
            case 'Null' :
            case 'Void' :
                $atom->noDelimiter = '';
                break;

            case 'Staticclass' :
                $atom->noDelimiter = $atom->fullcode;
                break;
    
            case 'Parenthesis' :
                $atom->noDelimiter = $extras['CODE']->noDelimiter;
                break;
    
            case 'Addition' :
                if ($atom->code === '+') {
                    $atom->noDelimiter = (int) $extras['LEFT']->noDelimiter +
                                         (int) $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '-') {
                    $atom->noDelimiter = (int) $extras['LEFT']->noDelimiter -
                                         (int) $extras['RIGHT']->noDelimiter;
                }
                break;

            case 'Multiplication' :
                if ($atom->code === '*') {
                    $atom->noDelimiter = (string) ((int) $extras['LEFT']->noDelimiter * (int) $extras['RIGHT']->noDelimiter);
                } elseif ($atom->code === '/' && (int) $extras['RIGHT']->noDelimiter != 0) {
                    $atom->noDelimiter = (string) ((int) $extras['LEFT']->noDelimiter / (int) $extras['RIGHT']->noDelimiter);
                } elseif ($atom->code === '%' && (int) $extras['RIGHT']->noDelimiter != 0) {
                    $atom->noDelimiter = (string) ((int) $extras['LEFT']->noDelimiter % (int) $extras['RIGHT']->noDelimiter);
                }
                break;

            case 'Power' :
                $atom->noDelimiter = ((int) $extras['LEFT']->noDelimiter) ** (int) $extras['RIGHT']->noDelimiter;
                if (is_nan($atom->noDelimiter) || is_infinite($atom->noDelimiter)) {
                    $atom->noDelimiter = '';
                }
                break;

            case 'Arrayliteral' :
                $atom->noDelimiter    = 'Array';
                break;

            case 'Not' :
                if ($atom->code === '!') {
                    $atom->noDelimiter = !$extras['NOT']->noDelimiter;
                } elseif ($atom->code === '~') {
                    $atom->noDelimiter = ~$extras['NOT']->noDelimiter;
                }
                break;

            case 'Logical' :
                if ($atom->code === '|') {
                    if (is_string($extras['LEFT']->noDelimiter) && is_string($extras['RIGHT']->noDelimiter)) {
                        $atom->noDelimiter = $extras['LEFT']->noDelimiter | $extras['RIGHT']->noDelimiter;
                    } else {
                        $atom->noDelimiter = (string) ((int) $extras['LEFT']->noDelimiter | (int) $extras['RIGHT']->noDelimiter);
                    }
                } elseif ($atom->code === '&') {
                    if (is_string($extras['LEFT']->noDelimiter) && is_string($extras['RIGHT']->noDelimiter)) {
                        $atom->noDelimiter = $extras['LEFT']->noDelimiter & $extras['RIGHT']->noDelimiter;
                    } else {
                        $atom->noDelimiter = (string) ((int) $extras['LEFT']->noDelimiter & (int) $extras['RIGHT']->noDelimiter);
                    }
                } elseif ($atom->code === '^') {
                    if (is_string($extras['LEFT']->noDelimiter) && is_string($extras['RIGHT']->noDelimiter)) {
                        $atom->noDelimiter = $extras['LEFT']->noDelimiter ^ $extras['RIGHT']->noDelimiter;
                    } else {
                        $atom->noDelimiter = (string) ((int) $extras['LEFT']->noDelimiter ^ (int) $extras['RIGHT']->noDelimiter);
                    }
                } elseif ($atom->code === '&&' || mb_strtolower($atom->code) === 'and') {
                    $atom->noDelimiter = (int) $extras['LEFT']->noDelimiter && (int) $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '||' || mb_strtolower($atom->code) === 'or') {
                    $atom->noDelimiter = (int) $extras['LEFT']->noDelimiter && (int) $extras['RIGHT']->noDelimiter;
                } elseif (mb_strtolower($atom->code) === 'xor') {
                    $atom->noDelimiter = ((int) $extras['LEFT']->noDelimiter xor (int) $extras['RIGHT']->noDelimiter);
                }
                break;

            case 'Heredoc' :
            case 'Concatenation' :
                $noDelimiters = array_column($extras, 'noDelimiter');
                $atom->noDelimiter = implode('', $noDelimiters);
                
                break;

            case 'Ternary' :
                if ($extras['CONDITION']->noDelimiter) {
                    $atom->noDelimiter = $extras['THEN']->noDelimiter;
                } else {
                    $atom->noDelimiter = $extras['ELSE']->noDelimiter;
                }
                break;

            case 'Coalesce' :
                if ($extras['LEFT']->noDelimiter) {
                    $atom->noDelimiter = $extras['LEFT']->noDelimiter;
                } else {
                    $atom->noDelimiter = $extras['RIGHT']->noDelimiter;
                }
                break;

            case 'Bitshift' :
                if ((int) $extras['RIGHT']->noDelimiter <= 0) {
                    // This would generate an error
                    $atom->noDelimiter = '';
                } elseif ($atom->code === '>>') {
                    $atom->noDelimiter = (int) $extras['LEFT']->noDelimiter >> (int) $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '<<') {
                    $atom->noDelimiter = (int) $extras['LEFT']->noDelimiter << (int) $extras['RIGHT']->noDelimiter;
                }
                break;

            case 'Comparison' :
                if ($atom->code === '==') {
                    $atom->noDelimiter = $extras['LEFT']->noDelimiter == $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '===') {
                    $atom->noDelimiter = $extras['LEFT']->noDelimiter === $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '!=' || $atom->code === '<>') {
                    $atom->noDelimiter = $extras['LEFT']->noDelimiter != $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '!==') {
                    $atom->noDelimiter = $extras['LEFT']->noDelimiter !== $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '>') {
                    $atom->noDelimiter = $extras['LEFT']->noDelimiter > $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '<') {
                    $atom->noDelimiter = $extras['LEFT']->noDelimiter < $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '>=') {
                    $atom->noDelimiter = $extras['LEFT']->noDelimiter >= $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '<=') {
                    $atom->noDelimiter = $extras['LEFT']->noDelimiter <= $extras['RIGHT']->noDelimiter;
                } elseif ($atom->code === '<=>') {
                    $atom->noDelimiter = $extras['LEFT']->noDelimiter <=> $extras['RIGHT']->noDelimiter;
                }
                break;

            case 'Functioncall' :
                if (in_array($atom->fullnspath, array('\basename', '\dirname'))) {
                    $function = $atom->fullnspath;
                    $atom->noDelimiter = $function($extras[0]->noDelimiter);
                } // else, ignore it
                break;

            case 'Self' :
            case 'Parent' :
                $atom->noDelimiter = strtolower($atom->atom);

                break;

        default :
            // Nothing, really
        }
    }
}

?>
