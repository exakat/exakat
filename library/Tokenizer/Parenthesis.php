<?php

namespace Tokenizer;

class Parenthesis extends TokenAuto {
    static public $operators = array('T_OPEN_PARENTHESIS');
    
    function _check() {
        $operands    = array('Addition', 'Multiplication', 'Sequence', 'String', 
                             'Integer', 'Float', 'Not', 'Variable','Array', 'Concatenation', 'Sign',
                             'Functioncall', 'Boolean', 'Comparison', 'Parenthesis', 'Constant', 'Array',
                             'Instanceof', 'Noscream', 'Magicconstant', 'Logical', 'Ternary',
                             'Assignation', 'Property', 'Staticproperty', 'Staticconstant',
                             'Methodcall', 'Staticmethodcall', 'Bitshift', 
                              );

        $this->conditions = array(-1 => array('filterOut2' => array('T_STRING', 'T_CATCH', 'T_EXIT', 'T_FOR', 'T_SWITCH', 'T_WHILE', 'T_ECHO', 'T_UNSET', 'T_EMPTY', 'T_PRINT', 'T_VARIABLE', 'T_ISSET', 'T_ARRAY', 'T_EVAL', 'T_LIST',  )), 
                                   0 => array('token' => Parenthesis::$operators,
                                              'atom' => 'none' ),
                                   1 => array('atom' => $operands),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom' => 'none'),
        );
        
        $this->actions = array('makeEdge' => array( '1' => 'CODE'),
                               'dropNext' => array(1),
                               'atom'     => 'Parenthesis',
                               'cleanIndex' => true);
        
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}
?>