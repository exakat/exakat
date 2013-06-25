<?php

namespace Tokenizer;

class Parenthesis extends TokenAuto {
    function _check() {
        $operands    = array('Addition', 'Multiplication', 'Sequence', 'String', 
                             'Integer', 'Float', 'Not', 'Variable','_Array', 'Concatenation', 'Sign',
                             'Functioncall', 'Boolean', 'Comparison', 'Parenthesis', 'Constant', 'Array',
                             'Instanceof', 'Noscream', 'Magicconstant', 'Logical', );

        $this->conditions = array(-1 => array('filterOut2' => array('T_STRING', 'T_ECHO', 'T_PRINT', 'T_VARIABLE', 'T_ISSET')), 
                                   0 => array('code' => '(',
                                              'atom' => 'none' ),
                                   1 => array('atom' => $operands),
                                   2 => array('code' => ')',
                                              'atom' => 'none'),
        );
        
        $this->actions = array('makeEdge' => array( '1' => 'CODE'),
                               'dropNext' => array(1),
                               'atom'     => 'Parenthesis');
        
        return $this->checkAuto();
    }
}
?>