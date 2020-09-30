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

use Exakat\Tasks\Load;

class Constant extends Plugin {
    public $name = 'constant';
    public $type = 'boolean';

    private $deterministFunctions = array();

    private $skipAtoms = array('Trait'         => 1,
                              'Class'          => 1,
                              'Classanonymous' => 1,
                              'Interface'      => 1,
                             );

    public function __construct() {
        parent::__construct();

        $deterministFunctions = exakat('methods')->getDeterministFunctions();
        $this->deterministFunctions = array_map(function ($x) { return "\\$x";}, $deterministFunctions);
    }

    public function run(Atom $atom, array $extras = array()): void {
        if (isset($this->skipAtoms[$atom->atom])) {
            return;
        }

        foreach($extras as $extra) {
            if ($extra->constant === null)  {
                $atom->constant = null;
                return ;
            }
        }

        switch ($atom->atom) {
            case 'Integer' :
            case 'Float' :
            case 'Boolean' :
            case 'Null' :
            case 'Void' :
            case 'Nsname' :
            case 'Identifier' :
            case 'Staticclass' :
            case 'Name' :
                $atom->constant = true;
                break;

            case 'Addition' :
            case 'Multiplication' :
            case 'Logical' :
            case 'Coalesce' :
            case 'Bitshift' :
            case 'Comparison' :
                $atom->constant = $extras['LEFT']->constant && $extras['RIGHT']->constant;
                break;

            case 'Heredoc' :
                if ($atom->heredoc !== true) { // it is a now doc
                    $atom->constant = true;
                    break;
                }
                $constants = array_column($extras, 'constant');
                $atom->constant = array_reduce($constants, function ($carry, $item) { return $carry && $item; }, true);
                break;

            case 'String' :
            case 'Arrayliteral' :
            case 'Concatenation' :
            case 'Argument' :
            case 'Classalias':
            case 'Sequence' :
                $constants = array_column($extras, 'constant');
                $atom->constant = array_reduce($constants, function ($carry, $item) { return $carry && $item; }, true);
                break;

            case 'Return' :
                $atom->constant = $extras['RETURN']->constant;
                break;

            case 'Staticconstant' :
                $atom->constant = $extras['CLASS']->constant;
                break;

            case 'Not' :
                $atom->constant = $extras['NOT']->constant;
                break;

            case 'Keyvalue' :
                $atom->constant = $extras['INDEX']->constant && $extras['VALUE']->constant;
                break;

            case 'Parenthesis' :
                $atom->constant = $extras['CODE']->constant;
                break;

            case 'Yield' :
                $atom->constant = $extras['YIELD']->constant;
                break;

            case 'Defineconstant' :
                if (empty($extras)) {
                    break;
                }
                $atom->constant = $extras['NAME']->constant && ($extras['VALUE']->constant ?? false);
                break;

            case 'Ternary' :
                $atom->constant = $extras['CONDITION']->constant &&
                                  $extras['THEN']->constant      &&
                                  $extras['ELSE']->constant;
                break;

            case 'Closure' :
                $atom->constant = true;
                break;

            case 'Assignation' :
                $atom->constant = $extras['RIGHT']->constant && $atom->code === '=';
                break;

            case 'Functioncall' :
                if (empty($atom->fullnspath)) {
                    $atom->constant  = Load::NOT_CONSTANT_EXPRESSION;
                } elseif (in_array($atom->fullnspath, $this->deterministFunctions)) {
                    if (isset($extras[0])) {
                        $constants = array_column($extras, 'constant');
                        $atom->constant = array_reduce($constants, function ($carry, $item) { return $carry && $item; }, true);
                    } else {
                        $atom->constant  = Load::CONSTANT_EXPRESSION;
                    }
                } else {
                    $atom->constant  = Load::NOT_CONSTANT_EXPRESSION;
                }
                break;

            default :
                $atom->constant = Load::NOT_CONSTANT_EXPRESSION;
        }
    }
}

?>
