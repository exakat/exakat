<?php declare(strict_types = 1);
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

class Boolval extends Plugin {
    const NO_VALUE = false;

    public $name = 'boolean';
    public $type = 'boolean';

    public function run(Atom $atom, array $extras): void {
        // Special case for Arraylist, so it won't be blocked by the filter behind.
        switch ($atom->atom) {
            case 'Arrayliteral' :
                $atom->boolean = (bool) $atom->count;
                return;

            case 'Assignation' :
                $atom->boolean = (bool) $extras['RIGHT']->boolean;
                return;

            case 'Trait' :
            case 'Class' :
            case 'Classanonymous' :
            case 'Interface' :
                return;

        }

        foreach($extras as $extra) {
            if ($extra->intval === self::NO_VALUE)  {
                $atom->intval = self::NO_VALUE;
                return ;
            }
        }

        // Ignoring $extras['LEFT'] === null
        if ($atom->atom === 'Assignation') {

            return;
        }

        switch ($atom->atom) {
            case 'Staticclass'   :
            case 'Self'          :
            case 'Parent'        :
            case 'Closure'       :
//            case 'Sequence'      :
            case 'Magicconstant' :
                $atom->boolean = true;
                break;

            case 'Identifier' :
                // $atom->code is a string
                $atom->boolean = (bool) (string) $atom->code;
                break;

            case 'Constant' :
                $atom->boolean    = $extras['VALUE']->boolean;
                break;

            case 'Nsname' :
                // when it is a string, there is no fallback
                $atom->boolean = false;
                break;

            case 'Float' :
                // $atom->code is a string
                $atom->boolean = (bool) (float) $atom->code;
                break;

            case 'Integer' :
                // similar to the case of Intval, but tailored for boolean
                if (strtolower(substr($atom->code, 0, 2)) === '0b') {
                    $actual = bindec(substr($atom->code, 2));
                } elseif (strtolower(substr($atom->code, 0, 2)) === '0x') {
                    $actual = hexdec(substr($atom->code, 2));
                } elseif (strtolower($atom->code[0]) === '0') {
                    // PHP 7 will just stop.
                    // PHP 5 will work until it fails
                    $actual = octdec(substr($atom->code, 1));
                } elseif ($atom->code[0] === '+' || $atom->code[0] === '-') {
                    $actual = pow(-1, substr_count($atom->code, '-')) * (int) strtr($atom->code, '+-', '  ');
                } else {
                    $actual = $atom->code;
                }

                $atom->boolean = (bool) $actual;
                break;

            case 'Boolean' :
                $atom->boolean = (mb_strtolower(trim($atom->code, '\\')) === 'true');
                break;

            case 'String' :
            case 'Heredoc' :
                $atom->boolean = (trimOnce($atom->code) !== '');
                break;

            case 'Null' :
            case 'Void' :
                $atom->boolean = false;
                break;

            case 'Parenthesis' :
                $atom->boolean = $extras['CODE']->boolean;
                break;

            case 'Addition' :
                if ($atom->code === '+') {
                    $atom->boolean = (bool) ((int) $extras['LEFT']->code + (int) $extras['RIGHT']->code);
                } elseif ($atom->code === '-') {
                    $atom->boolean = (bool) ((int) $extras['LEFT']->code - (int) $extras['RIGHT']->code);
                }
                break;

            case 'Multiplication' :
                if ($atom->code === '*') {
                    $atom->boolean = (bool) ((int) $extras['LEFT']->boolean * (int) $extras['RIGHT']->boolean);
                } elseif ($atom->code === '/') {
                    if ((int) $extras['RIGHT']->boolean === 0) {
                        $atom->boolean = false;
                    } else {
                        $atom->boolean = (bool) ((int) $extras['LEFT']->boolean / (int) $extras['RIGHT']->boolean);
                    }
                } elseif ($atom->code === '%') {
                    if ((int) $extras['RIGHT']->boolean === 0) {
                        $atom->boolean = false;
                    } else {
                        $atom->boolean = (bool) ((int) $extras['LEFT']->boolean % (int) $extras['RIGHT']->boolean);
                    }
                }
                break;

            case 'Power' :
                $atom->boolean = (bool) (((bool) $extras['LEFT']->boolean) ** (bool) $extras['RIGHT']->boolean);
                break;

            case 'Keyvalue' :
                $atom->boolean = (bool) ($extras['INDEX']->boolean && $extras['VALUE']->boolean);
                break;

            case 'Not' :
                if ($atom->code === '!') {
                    $atom->boolean = !$extras['NOT']->boolean;
                } elseif ($atom->code === '~') {
                    $atom->boolean = (bool) ~$extras['NOT']->intval;
                }
                break;

            case 'Logical' :
                if ($atom->code === '|') {
                    if (is_string($extras['LEFT']->boolean) && is_string($extras['RIGHT']->boolean)) {
                        $atom->boolean = (bool) ($extras['LEFT']->boolean | $extras['RIGHT']->boolean);
                    } else {
                        $atom->boolean = (bool) ($extras['LEFT']->boolean | (int) $extras['RIGHT']->boolean);
                    }
                } elseif ($atom->code === '&') {
                    if (is_string($extras['LEFT']->boolean) && is_string($extras['RIGHT']->boolean)) {
                        $atom->boolean = (bool) ($extras['LEFT']->boolean & $extras['RIGHT']->boolean);
                    } else {
                        $atom->boolean = (bool) ($extras['LEFT']->boolean & (int) $extras['RIGHT']->boolean);
                    }
                } elseif ($atom->code === '^') {
                    if (is_string($extras['LEFT']->boolean) && is_string($extras['RIGHT']->boolean)) {
                        $atom->boolean = (bool) ($extras['LEFT']->boolean ^ $extras['RIGHT']->boolean);
                    } else {
                        $atom->boolean = (bool) ($extras['LEFT']->boolean ^ (int) $extras['RIGHT']->boolean);
                    }
                } elseif ($atom->code === '&&' || mb_strtolower($atom->code) === 'and') {
                    $atom->boolean = $extras['LEFT']->boolean && $extras['RIGHT']->boolean;
                } elseif ($atom->code === '||' || mb_strtolower($atom->code) === 'or') {
                    $atom->boolean = $extras['LEFT']->boolean && $extras['RIGHT']->boolean;
                } elseif (mb_strtolower($atom->code) === 'xor') {
                    $atom->boolean = ($extras['LEFT']->boolean xor $extras['RIGHT']->boolean);
                } elseif ($atom->code === '<=>') {
                    $atom->boolean = (bool) ($extras['LEFT']->boolean <=> $extras['RIGHT']->boolean);
                }
                break;

            case 'Concatenation' :
                $boolean = array_column($extras, 'boolean');
                $atom->boolean = (bool) implode('', $boolean);
                break;

            case 'Ternary' :
                if ($extras['CONDITION']->boolean) {
                    $atom->boolean = $extras['THEN']->boolean;
                } else {
                    $atom->boolean = $extras['ELSE']->boolean;
                }
                break;

            case 'Bitshift' :
                if ($atom->code === '>>') {
                    $atom->boolean = (bool) ((int) $extras['LEFT']->boolean >> (int) $extras['RIGHT']->boolean);
                } elseif ($atom->code === '<<') {
                    $atom->boolean = (bool) ((int) $extras['LEFT']->boolean << (int) $extras['RIGHT']->boolean);
                }
                break;

            case 'Comparison' :
                if ($atom->code === '==') {
                    $atom->boolean = ($extras['LEFT']->boolean == $extras['RIGHT']->boolean);
                } elseif ($atom->code === '===') {
                    $atom->boolean = ($extras['LEFT']->boolean === $extras['RIGHT']->boolean);
                } elseif ($atom->code === '!=' || $atom->code === '<>') {
                    $atom->boolean = ($extras['LEFT']->boolean != $extras['RIGHT']->boolean);
                } elseif ($atom->code === '!==') {
                    $atom->boolean = ($extras['LEFT']->boolean !== $extras['RIGHT']->boolean);
                } elseif ($atom->code === '>') {
                    $atom->boolean = ($extras['LEFT']->boolean > $extras['RIGHT']->boolean);
                } elseif ($atom->code === '<') {
                    $atom->boolean = ($extras['LEFT']->boolean < $extras['RIGHT']->boolean);
                } elseif ($atom->code === '>=') {
                    $atom->boolean = ($extras['LEFT']->boolean >= $extras['RIGHT']->boolean);
                } elseif ($atom->code === '<=') {
                    $atom->boolean = ($extras['LEFT']->boolean <= $extras['RIGHT']->boolean);
                }
                break;

            case 'Assignation' :
                if ($atom->code === '=') {
                    $atom->boolean =  $extras['RIGHT']->boolean;
                }
                break;

        default :
            $atom->boolean = false;
        }
    }
}

?>
