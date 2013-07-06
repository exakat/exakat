<?php

namespace Tokenizer;

class Parenthesis extends TokenAuto {
    function _check() {
        $operands    = array('Addition', 'Multiplication', 'Sequence', 'String', 
                             'Integer', 'Float', 'Not', 'Variable','_Array', 'Concatenation', 'Sign',
                             'Functioncall', 'Boolean', 'Comparison', 'Parenthesis', 'Constant', 'Array',
                             'Instanceof', 'Noscream', 'Magicconstant', 'Logical', 'Ternary',
                             'Assignation', 'Property', );

        $this->conditions = array(-1 => array('filterOut2' => array('T_STRING', 'T_EXIT', 'T_SWITCH', 'T_WHILE', 'T_ECHO', 'T_UNSET', 'T_EMPTY', 'T_PRINT', 'T_VARIABLE', 'T_ISSET')), 
                                   0 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'atom' => 'none' ),
                                   1 => array('atom' => $operands),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom' => 'none'),
        );
        
        $this->actions = array('makeEdge' => array( '1' => 'CODE'),
                               'dropNext' => array(1),
                               'atom'     => 'Parenthesis');
        
        return $this->checkAuto();
    }
}
?>