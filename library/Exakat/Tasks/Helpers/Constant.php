<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
use Exakat\Data\Methods;

class Constant extends Plugin {
    public $name = 'constant';
    public $type = 'boolean';

    private $deterministFunctions = array();
    
    public function __construct($config) {
        parent::__construct();
        
        $data = new Methods($config);
        $deterministFunctions = $data->getDeterministFunctions();
        $this->deterministFunctions = array_map(function ($x) { return "\\$x";}, $deterministFunctions);
    }
    
    public function run($atom, $extras = array()) {
        foreach($extras as $extra) {
            if ($extra->constant === null)  {
                $atom->constant = null;
                return ;
            }
        }

        switch ($atom->atom) {
            case 'Integer' :
            case 'Real' :
            case 'Boolean' :
            case 'Null' :
            case 'Void' :
            case 'Nsname' :
            case 'Identifier' :
            case 'Staticclass' :
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

            case 'String' :
            case 'Arrayliteral' :
            case 'Concatenation' :
            case 'Argument' :
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

            case 'Ternary' :
                $atom->constant = $extras['CONDITION']->constant &&
                                  $extras['THEN']->constant      &&
                                  $extras['ELSE']->constant;
                break;

            case 'Closure' :
                $atom->constant = true;
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
