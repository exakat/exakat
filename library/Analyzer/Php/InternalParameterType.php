<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
namespace Analyzer\Php;

use Analyzer;

class InternalParameterType extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Functions/IsExtFunction');
    }
    
    public function analyze() {
        $data = new \Data\Methods();
        $args = $data->getInternalParameterType();

        $typeConversion = array('string'   => 'String', //array('String', 'Heredoc', 'Magicconstant'),
                                'float'    => 'Float',
                                'int'      => 'Integer',
                                'numeric'  => array('Float', 'Integer'),
                                'resource' => '',
                                'bool'     => 'Boolean',
                                'array'    => '',
                                'void'     => 'Void');
        foreach($args as $position => $types) {
//            if ($position != 1) { continue; }

            foreach($types as $type => $functions) {
//                if ($type != 'string') { continue; }

                if (strpos($type, ',') !== false) {
                    continue; // No support for multiple type yet
                }

                if (!isset($typeConversion[$type]) || empty($typeConversion[$type])) {
                    continue;
                }
                
                $this->atomIs('Functioncall')
                     ->analyzerIs('Functions/IsExtFunction')
                     ->fullnspath($functions)
                     ->outIs('ARGUMENTS')
                     ->outIs('ARGUMENT')
                     ->is('rank', $position)

                     // only include literals
                     ->isLiteral()
                    // Closure ? Array ? 

                    // Constant (Identifier), logical, concatenation, addition ? 
                    // Those will have to be replaced after more research
//                     ->atomIsNot(array('Constant', 'Logical', 'Concatenation', 'Addition', 'Power', 'Multiplication'))

                    // All string equivalents 
                     ->atomIsNot($typeConversion[$type])
                     ->back('first');
                $this->prepareQuery();
            }
        }
    }
}

?>
