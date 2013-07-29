<?php

namespace Tokenizer;

class Bitshift extends TokenAuto {
    static public $operators = array('T_SR','T_SL');
    
    function _check() {
        // note : Multiplication:: and Bitshift:: operators are the same! 
        $this->conditions = array(-2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_DOT')),
                                  -1 => array('atom' => Multiplication::$operands ),
                                   0 => array('token' => Bitshift::$operators,
                                             'atom' => 'none'),
                                   1 => array('atom' => Multiplication::$operands),
                                   2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR',)),
        );
        
        $this->actions = array('makeEdge'    => array('1' => 'RIGHT',
                                                      '-1' => 'LEFT'
                                                      ),
                               'atom'       => 'Bitshift',
                               );
        return $this->checkAuto();
    }
}
?>