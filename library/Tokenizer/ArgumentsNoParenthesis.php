<?php

namespace Tokenizer;

class ArgumentsNoParenthesis extends TokenAuto {
    static public $operators = array('T_ECHO', 'T_PRINT', 'T_INCLUDE_ONCE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 'T_REQUIRE', 'T_EXIT');

    function _check() {
        // @note echo 's' : no parenthesis
        $this->conditions = array( 0 => array('atom' => 'none',
                                              'token' => ArgumentsNoParenthesis::$operators),
                                   1 => array('atom'  => 'yes', 'notAtom' => array('Sequence', 'Arguments')),
                                   2 => array('filterOut2' => array_merge(array('T_DOT', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_EQUAL', 'T_QUESTION', 'T_OPEN_PARENTHESIS', 'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_COMMA',),
                                                                          Addition::$operators, Multiplication::$operators, 
                                                                          Bitshift::$operators, Logical::$operators)) 
        );
        
        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'keepIndexed' => true);
        $this->checkAuto();

        // @note exit; no parenthesis, no argument. 
        $this->conditions = array( 0 => array('atom' => 'none',
                                              'token' => array('T_EXIT')),
                                   1 => array('token'  => 'T_SEMICOLON') 
        );
        
        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'keepIndexed' => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }
}
?>