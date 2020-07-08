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

use Exakat\Query\Query;

class NotCompatibleWithType extends DSL {
    public function run(): Command {
        assert(func_num_args() === 1, 'Wrong number of argument for ' . __METHOD__ . '. 1 is expected, ' . func_num_args() . ' provided');
        list($types) = func_get_args();

        $query = <<<GREMLIN
where( 
__.sideEffect{ typehints = []; }
  .out("TYPEHINT")
  .sideEffect{ typehints.add(it.get().value("fullnspath")) ; }
  .fold() 
)
.filter{
    results = true;
    for(typehint in typehints) {
        switch(typehint) {
            case "\\\\string":
                results = results && !($types in ["Magicconstant", "Heredoc", "String", "Concatenation", "Classconstant", "Shell"]);
                break;
                
            case "\\\\int":
                results = results && !($types in ["Integer", "Addition", "Multiplication", "Bitshift", "Logical", "Bitoperation", "Power", "Postplusplus", "Preplusplus", "Not"]);
                break;
    
            case "\\\\numeric":
                results = results && !($types in ["Integer", "Addition", "Multiplication", "Bitshift", "Logical", "Bitoperation", "Power", "Float", "Postplusplus", "Preplusplus"]);
                break;
    
            case "\\\\float":
                results = results && !($types in ["Float", "Addition", "Multiplication", "Bitshift", "Power"]);
                break;
    
            case "\\\\bool":
                results = results && !($types in ["Boolean", "Logical", "Not", "Comparison"]);
                break;
    
            case "\\\\array":
                results = results && !($types in ["Arrayliteral", "Addition"]);
                break;
    
            case "\\\\mixed":
                results = false; // anything is mixed, so this is always false
                break;

            case "\\\\null":
                results = results && !($types in ["Null"]);
                break;
    
            case "\\\\void":
            case "\\\\resource":
            default: 
                false;
        }
    }
    
    results;
}
GREMLIN;
        return new Command($query, array());
    }
}
?>
