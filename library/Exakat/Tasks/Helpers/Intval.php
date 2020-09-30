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

class Intval extends Plugin {
    const NO_VALUE = '';

    public $name = 'intval';
    public $type = 'integer';

    private $skipAtoms = array('Trait'         => 1,
                              'Class'          => 1,
                              'Classanonymous' => 1,
                              'Interface'      => 1,
                             );

    public function run(Atom $atom, array $extras): void {
        if (isset($this->skipAtoms[$atom->atom])) {
            return;
        }

        // Ignoring $extras['LEFT'] === null
        if ($atom->atom === 'Assignation') {
            if ($atom->code === '=') {
                $atom->intval =  $extras['RIGHT']->intval;
            }

            return;
        }

        foreach($extras as $extra) {
            if ($extra->intval === self::NO_VALUE)  {
                $atom->intval = self::NO_VALUE;
                return ;
            }
        }

        switch ($atom->atom) {
            case 'Integer' :
                $value = (string) $atom->code;

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

                $atom->intval = $actual == PHP_INT_MIN ? 0 : $actual;
                break;

            case 'Float' :
            case 'String' :
            case 'Heredoc' :
                if (empty($extras)) {
                    $atom->intval   = (int) trimOnce($atom->code);
                } else {
                    $atom->intval   = array_sum(array_column($extras, 'intval'));
                }
                break;

            case 'Boolean' :
                $atom->intval = (int) (mb_strtolower(trim($atom->code, '\\')) === 'true');
                break;

            case 'Staticclass' :
            case 'Identifier'  :
//            case 'Nsname'      : This leads to a fatal error
            case 'Self'        :
            case 'Parent'      :
            case 'Magicconstant' :
                $atom->intval = self::NO_VALUE;
                break;

            case 'Null'        :
            case 'Void'        :
                $atom->intval = 0;
                break;

            case 'Parenthesis' :
                $atom->intval = $extras['CODE']->intval;
                break;

            case 'Addition' :
                if ($atom->code === '+') {
                    $atom->intval = $extras['LEFT']->intval + $extras['RIGHT']->intval;
                } elseif ($atom->code === '-') {
                    $atom->intval = $extras['LEFT']->intval - $extras['RIGHT']->intval;
                }
                break;

            case 'Multiplication' :
                if ($atom->code === '*') {
                    $atom->intval = (int) ($extras['LEFT']->intval * $extras['RIGHT']->intval);
                } elseif ($atom->code === '/') {
                    if ((int) $extras['RIGHT']->intval === 0) {
                        $atom->intval = 0;
                    } else {
                        $atom->intval = intdiv((int) $extras['LEFT']->intval , (int) $extras['RIGHT']->intval);
                    }
                } elseif ($atom->code === '%') {
                    if ((int) $extras['RIGHT']->intval === 0) {
                        $atom->intval = 0;
                    } else {
                        $atom->intval = ($extras['LEFT']->intval % $extras['RIGHT']->intval);
                    }
                }
                break;

            case 'Power' :
                $atom->intval = ((int) $extras['LEFT']->intval) ** (int) $extras['RIGHT']->intval;
                if (is_nan($atom->intval) || is_infinite($atom->intval)) {
                    $atom->intval = 0;
                }
                break;

            case 'Arrayliteral' :
                $atom->intval    = (int) (bool) $atom->count;
                break;

            case 'Constant' :
                $atom->intval    = $extras['VALUE']->intval;
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
                    $atom->intval = ($extras['LEFT']->intval xor $extras['RIGHT']->intval);
                } elseif ($atom->code === '<=>') {
                    $atom->intval = $extras['LEFT']->intval <=> $extras['RIGHT']->intval;
                }
                break;

            case 'Concatenation' :
                $intval = array_column($extras, 'noDelimiter');
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
                if ($extras['RIGHT']->intval <= 0) {
                    // This would generate an error anyway
                    $atom->intval = 0;
                } elseif ($atom->code === '>>') {
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
//            case 'Name'        :
//        case 'Sequence' :
            // Nothing, really
        }
    }
}

?>
