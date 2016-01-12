<?php

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
