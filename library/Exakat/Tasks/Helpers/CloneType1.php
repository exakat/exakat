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

class CloneType1 extends Plugin {
    public $name = 'ctype1';
    public $type = 'string';

    public function run($atom, $extras) {
        foreach($extras as $extra) {
            if ($extra->ctype1 === null)  {
                $atom->ctype1 = null;
                return ;
            }
        }
        
        switch ($atom->atom) {
            case 'Integer' :
                $atom->ctype1 = 'i';
                break;

            case 'Real' :
                $atom->ctype1 = 'r';
                break;

            case 'String' :
                $atom->ctype1 = 's';
                break;

            case 'Identifier' :
            case 'Name' :
            case 'Methodcallname' :
            case 'Newcall' :
                $atom->ctype1 = 'n';
                break;

            case 'Nsname' :
                $atom->ctype1 = 'Name';
                break;

            case 'Phpvariable' :
                $atom->ctype1 = '$phpv';
                break;

            case 'Variable' :
                $atom->ctype1 = '$v';
                break;

            case 'Variableobject' :
                $atom->ctype1 = '$vo';
                break;

            case 'Variablearray' :
                $atom->ctype1 = '$va';
                break;

            case 'This' :
                $atom->ctype1 = '$this';
                break;

            case 'Comparison' :
            case 'Assignation' :
                $atom->ctype1 = $extras['LEFT']->ctype1 . $atom->code . $extras['RIGHT']->ctype1;
                break;

            case 'Addition' :
            case 'Multiplication' :
            case 'Logical' :
                if ($extras['LEFT']->ctype1 > $extras['RIGHT']->ctype1) {
                    $left = $extras['LEFT']->ctype1;
                    $right = $extras['RIGHT']->ctype1;
                } else {
                    $left = $extras['RIGHT']->ctype1;
                    $right = $extras['LEFT']->ctype1;
                }
                $atom->ctype1 = $left . strtolower($atom->code) . $right;
                break;

            case 'Keyvalue' :
                $atom->ctype1 = $extras['INDEX']->ctype1 . '=>' . $extras['VALUE']->ctype1;
                break;

            case 'Power' :
                $atom->ctype1 = $extras['LEFT']->ctype1 . $atom->code . $extras['RIGHT']->ctype1;
                break;

            case 'Bitshift' :
                $atom->ctype1 = $extras['LEFT']->ctype1 . $atom->code . $extras['RIGHT']->ctype1;
                break;

            case 'Postplusplus' :
                $atom->ctype1 = $extras['POSTPLUSPLUS']->ctype1 . $atom->code;
                break;

            case 'Preplusplus' :
                $atom->ctype1 = $atom->code . $extras['PREPLUSPLUS']->ctype1;
                break;

            case 'Ifthen' :
            case 'Ternary' : 
                $atom->ctype1 = 'if ('.$extras['CONDITION']->ctype1.') '.$extras['THEN']->ctype1;
                if (isset($extras['ELSE'])) {
                    $atom->ctype1 .= 'else '.$extras['ELSE']->ctype1;
                }
                break;

            case 'Shell' :
                $ctype1 = array_column($extras, 'ctype1');
                $atom->ctype1 = '`'.implode(' ', $ctype1).'`';
                break;

            case 'Cast' :
                $atom->ctype1 = strtolower($atom->code).$extras['CAST']->ctype1;
                break;

            case 'Clone' :
                $atom->ctype1 = 'clone '.$extras['CLONE']->ctype1;
                break;

            case 'Sign' :
                $atom->ctype1 = strtolower($atom->code).$extras['SIGN']->ctype1;
                break;

            case 'Heredoc' :
                $ctype1 = array_column($extras, 'ctype1');
                $atom->ctype1 = implode('.', $ctype1);
                break;

            case 'Concatenation' :
                $ctype1 = array_column($extras, 'ctype1');
                $atom->ctype1 = implode('.', $ctype1);
                break;

            case 'Sequence' :
                $ctype1 = array_column($extras, 'ctype1');
                $atom->ctype1 = implode(';', $ctype1);
                break;

            case 'Switch' :
                $atom->ctype1 = 'switch ('.$extras['NAME']->ctype1 . ') ' . $extras['CASES']->ctype1;
                break;

            case 'While' :
                $atom->ctype1 = 'while ('.$extras['CONDITION']->ctype1 . ') ' . $extras['BLOCK']->ctype1;
                break;

            case 'Dowhile' :
                $atom->ctype1 = 'do '.$extras['BLOCK']->ctype1 . ' while( ' . $extras['CONDITION']->ctype1.')';
                break;

            case 'Case' :
                $atom->ctype1 = 'case '.$extras['CASE']->ctype1 . ':' . $extras['CODE']->ctype1;
                break;

            case 'Default' :
                $atom->ctype1 = 'default : '. $extras['CODE']->ctype1;
                break;

            case 'Array' :
                $atom->ctype1 = $extras['VARIABLE']->ctype1 . '[' . $extras['INDEX']->ctype1 .']';
                break;

            case 'Break' :
                $atom->ctype1 = strtolower($atom->atom).$extras['BREAK']->ctype1;
                break;

            case 'Continue' :
                $atom->ctype1 = strtolower($atom->atom).$extras['CONTINUE']->ctype1;
                break;

            case 'Try' :
                $atom->ctype1 = 'try '.implode('; ', array_column($extras, 'ctype1'));
                break;

            case 'Catch' :
                $block = $extras['BLOCK'];
                $variable = $extras['VARIABLE'];
                $classes = array_slice($extras, 0, -2);

                $atom->ctype1 = 'catch('.implode('|', array_column($classes, 'ctype1')). ' '.$variable->ctype1.')'. $extras['BLOCK']->ctype1;
                break;

            case 'Finally' :
                $atom->ctype1 = 'finally'. $extras['BLOCK']->ctype1;
                break;

            case 'Methodcall' :
                $atom->ctype1 = $extras['OBJECT']->ctype1 . $atom->code . $extras['METHOD']->ctype1;
                break;

            case 'Member' :
                $atom->ctype1 = $extras['OBJECT']->ctype1 . $atom->code . $extras['MEMBER']->ctype1;
                break;

            case 'Staticconstant' :
                $atom->ctype1 = $extras['CLASS']->ctype1 . $atom->code . $extras['CONSTANT']->ctype1;
                break;

            case 'Staticproperty' :
                $atom->ctype1 = $extras['CLASS']->ctype1 . $atom->code . $extras['MEMBER']->ctype1;
                break;

            case 'Staticmethodcall' :
                $atom->ctype1 = $extras['CLASS']->ctype1 . $atom->code . $extras['METHOD']->ctype1;
                break;

            case 'For' :
                $atom->ctype1 = 'for('.$extras['INIT']->ctype1.';'.$extras['FINAL']->ctype1.';'.$extras['INCREMENT']->ctype1.')'.$extras['BLOCK']->ctype1;
                break;

            case 'Foreach' :
                $atom->ctype1 = 'foreach('.$extras['SOURCE']->ctype1.' as '.$extras['VALUE']->ctype1.')'.$extras['BLOCK']->ctype1;
                break;

            case 'Arrayappend' :
                $atom->ctype1 = $extras['APPEND']->ctype1 . '[]';
                break;

            case 'Instanceof' :
                $atom->ctype1 = $extras['CLASS']->ctype1 . 'instanceof'. $extras['VARIABLE']->ctype1;
                break;

            case 'Magicconstant' :
                $atom->ctype1 = $atom->code;
                break;

            case 'Not' :
                $atom->ctype1 = $atom->code . $extras['NOT']->ctype1;
                break;

            case 'Noscream' :
                $atom->ctype1 = $atom->code . $extras['AT']->ctype1;
                break;

            case 'New' :
                $atom->ctype1 = $atom->code . $extras['NEW']->ctype1;
                break;

            case 'Throw' :
                $atom->ctype1 = $atom->code . $extras['THROW']->ctype1;
                break;

            case 'Return' :
                $atom->ctype1 = $atom->code . $extras['RETURN']->ctype1;
                break;

            case 'Void' :
                $atom->ctype1 = 'V';
                break;

            case 'Null' :
                $atom->ctype1 = 'N';
                break;

            case 'Boolean' :
                $atom->ctype1 = 'B';
                break;

            case 'Self' :
                $atom->ctype1 = 'S';
                break;

            case 'Parent' :
                $atom->ctype1 = 'P';
                break;

            case 'Static' :
                $atom->ctype1 = 'St';
                break;

            case 'Functioncall' :
            case 'Echo'   : 
            case 'Print'  : 
            case 'Include': 
            case 'Empty'  : 
            case 'Isset'  : 
            case 'List'   : 
            case 'Unset'  :
            case 'Exit'   :
            case 'Eval'   :
                $ctype1 = array_column($extras, 'ctype1');
                $atom->ctype1 = strtolower($atom->atom) . '('.implode(',', $ctype1).')';
                break;

            case 'Parenthesis': 
                $atom->ctype1 = '('.$extras['CODE']->ctype1.')';
                break;

            case 'Function' :
            case 'Method' :
            case 'Closure' :
            case 'Defineconstant' :
            case 'Magicmethod' :
                $ctype1 = array_column($extras, 'ctype1');
                $atom->ctype1 = $atom->code . '('.implode(',', $ctype1).')';
                break;
                
            case 'Arrayliteral' :
                $ctype1 = array_column($extras, 'ctype1');
                $atom->ctype1 = $atom->code . '('.implode(',', $ctype1).')';
                break;

        default :
            static $i = 0;
            
            $atom->ctype1 = 'default '.$atom->atom.' '.++$i;
            
            print "CLONE DEFAULT : $atom->atom\n";
        }
    }
}

?>
