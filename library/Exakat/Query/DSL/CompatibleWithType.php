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


namespace Exakat\Query\DSL;


class CompatibleWithType extends DSL {
    public function run(): Command {
        switch(func_num_args()) {
            case 2 :
                list($types, $withNull) = func_get_args();
                $withNull = in_array($withNull, array(NotCompatibleWithType::ALLOW_NULL, NotCompatibleWithType::DISALLOW_NULL), STRICT_COMPARISON) ? $withNull : NotCompatibleWithType::DISALLOW_NULL;
                break;

            case 1:
                list($types) = func_get_args();
                $withNull = NotCompatibleWithType::DISALLOW_NULL;
                break;

            default:
                assert(func_num_args() <= 2, 'Wrong number of argument for ' . __METHOD__ . '. 2 are expected, ' . func_num_args() . ' provided');
        }

        if ($withNull === NotCompatibleWithType::ALLOW_NULL) {
            $withNullGremlin = '.not(hasLabel("Null"))';
        } else {
            $withNullGremlin = '';
        }

        $query = <<<GREMLIN
where( 
__.sideEffect{ typehints = []; }
  .out("TYPEHINT", "RETURNTYPE")
  .has("fullnspath")
  $withNullGremlin
  .sideEffect{ typehints.add(it.get().value("fullnspath")) ; }
  .fold() 
)
.filter{
    results = false;
    for(typehint in typehints) {
        switch(typehint) {
            case "\\\\string":
                results = results || ($types in ["Magicconstant", "Heredoc", "String", "Concatenation", "Staticclass", "Shell"]);
                break;
                
            case "\\\\int":
                results = results || ($types in ["Integer", "Addition", "Multiplication", "Bitshift", "Logical", "Bitoperation", "Power", "Postplusplus", "Preplusplus", "Not"]);
                break;
    
            case "\\\\numeric":
                results = results || ($types in ["Integer", "Addition", "Multiplication", "Bitshift", "Logical", "Bitoperation", "Power", "Float", "Postplusplus", "Preplusplus"]);
                break;
    
            case "\\\\float":
                results = results || ($types in ["Float", "Addition", "Multiplication", "Bitshift", "Power"]);
                break;
    
            case "\\\\bool":
                results = results || ($types in ["Boolean", "Logical", "Not", "Comparison"]);
                break;
    
            case "\\\\array":
                results = results || ($types in ["Arrayliteral", "Addition"]);
                break;
    
            case "\\\\mixed":
                results = true; // anything is mixed, so this is always false
                break;

            case "\\\\null":
                results = results || !($types in ["Null"]);
                break;
    
            case "\\\\void":
            case "\\\\resource":
            default: 
                true;
        }
    }
    
    results;
}
GREMLIN;
        return new Command($query, array());
    }
}
?>
