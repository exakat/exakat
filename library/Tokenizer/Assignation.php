<?php

namespace Tokenizer;

class Assignation extends TokenAuto {
    function _check() {

        $operands = array('Integer', 'Multiplication', 'Addition', 'Not',
                          'Array', 'Float', 'Concatenation', 'Property',
                          'Parenthesis', 'Noscream', 'Ternary', );
        
        $this->conditions = array(-1 => array('atom' => array('Variable', 'Array', 'Property')),
                                  0 => array('code' => array('='),
                                             'atom' => 'none'),
                                  1 => array('atom' => $operands),
                                  
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'),
                               'atom'       => 'Assignation');

        return $this->checkAuto();
    } 
}
?>