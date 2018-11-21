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

class Nullval extends Plugin {
    public $name = 'isNull';
    public $type = 'boolean';

    public function run($atom, $extras) {
        foreach($extras as $extra) {
            if ($extra->isNull === null)  {
                $atom->isNull = true;
                return ;
            }
        }

        switch ($atom->atom) {
            case 'Boolean' :
            case 'Integer' :
            case 'Real' :
            case 'String' :
            case 'Heredoc' :
            case 'Addition' :
            case 'Multiplication' :
            case 'Power' :
            case 'Arrayliteral' :
            case 'Not' :
            case 'Logical' :
            case 'Heredoc' :
            case 'Concatenation' :
            case 'Bitshift' :
            case 'Comparison' :
            case 'Staticclass' :
                $atom->isNull = false;
                break;

            case 'Null' :
            case 'Void' :
                $atom->isNull = true;
                break;
    
            case 'Parenthesis' :
                $atom->isNull = $extras['CODE']->isNull;
                break;

            case 'Ternary' :
                if ($extras['CONDITION']->isNull) {
                    $atom->isNull = $extras['THEN']->isNull;
                } else {
                    $atom->isNull = $extras['ELSE']->isNull;
                }
                break;

            case 'Constant' :
                $atom->isNull = $extras['VALUE']->isNull;
                break;

            case 'Coalesce' :
                if ($extras['LEFT']->isNull) {
                    $atom->isNull = $extras['LEFT']->isNull;
                } else {
                    $atom->isNull = $extras['RIGHT']->isNull;
                }
                break;

        default :
            
        }
    }
}

?>
