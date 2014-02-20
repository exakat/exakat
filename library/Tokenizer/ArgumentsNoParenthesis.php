<?php

namespace Tokenizer;

class ArgumentsNoParenthesis extends TokenAuto {
    static public $operators = array('T_ECHO', 'T_PRINT', 'T_INCLUDE_ONCE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 
                                     'T_REQUIRE', 'T_EXIT', 'T_STATIC', );

    function _check() {
        // @note echo 's' : no parenthesis
        $this->conditions = array( -1 => array('filterOut'  => array('T_PUBLIC', 'T_PRIVATE', 'T_PROTECTED', 'T_FINAL', 'T_ABSTRACT')),
                                    0 => array('atom'       => 'none',
                                               'token'      => ArgumentsNoParenthesis::$operators,
                                               'notToken'   => 'T_STATIC'),
                                    1 => array('atom'       => 'yes', 
                                               'notAtom'    => array('Sequence', 'Arguments', 'Function', 
                                                                   'Ppp', 'Final', 'Abstract')),
                                    2 => array('filterOut2' => array_merge(array('T_DOT', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 
                                                                           'T_EQUAL', 'T_QUESTION', 'T_OPEN_PARENTHESIS', 
                                                                           'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_COMMA', ),
                                                                           Addition::$operators, Multiplication::$operators, 
                                                                           Bitshift::$operators, Logical::$operators,
                                                                           Postplusplus::$operators, Comparison::$operators)) 
        );
        
        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'keepIndexed' => true,
                               'property'    => array('noParenthesis' => 'true'));
        $this->checkAuto();

        // @note exit; no parenthesis, no argument. 
        $this->conditions = array( -1 => array('notToken' => 'T_INSTANCEOF'),
                                    0 => array('atom' => 'none',
                                               'token' => array('T_EXIT', 'T_STATIC',)),
                                    1 => array('token'  => 'T_SEMICOLON') 
        );
        
        $this->actions = array('addEdge'     => array(0 => array('Arguments' => 'ARGUMENT')),
                               'keepIndexed' => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }
}
?>