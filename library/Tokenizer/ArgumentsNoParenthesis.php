<?php

namespace Tokenizer;

class ArgumentsNoParenthesis extends Arguments {
    static public $operators = array('T_ECHO', 'T_PRINT', 'T_INCLUDE_ONCE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 
                                     'T_REQUIRE', 'T_EXIT', 'T_STATIC', );
    static public $atom = 'Arguments';

    public function _check() {
        // @note print 's' : no parenthesis
        $this->conditions = array( -1 => array('filterOut'  => array('T_PUBLIC', 'T_PRIVATE', 'T_PROTECTED', 'T_FINAL', 'T_ABSTRACT')),
                                    0 => array('atom'       => 'none',
                                               'token'      => array('T_PRINT', 'T_INCLUDE_ONCE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 
                                                                     'T_REQUIRE', 'T_EXIT') ),
                                    1 => array('atom'       => 'yes', 
                                               'notAtom'    => array('Sequence', 'Arguments', 'Function', 
                                                                     'Ppp', 'Final', 'Abstract')),
                                    2 => array('filterOut2' => array_merge(array('T_DOT', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 
                                                                           'T_EQUAL', 'T_QUESTION', 'T_OPEN_PARENTHESIS', 
                                                                           'T_OPEN_BRACKET', 'T_OPEN_CURLY',),
                                                                           Addition::$operators, Multiplication::$operators, 
                                                                           Power::$operators, 
                                                                           Bitshift::$operators, Logical::$booleans,
                                                                           Postplusplus::$operators, Comparison::$operators)) 
        );
        
        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'rank'        => array(1 => '0'),
                               'keepIndexed' => true);
        $this->checkAuto();

        // @note special case for echo 's' : no parenthesis
        $this->conditions = array( -1 => array('filterOut'  => array('T_PUBLIC', 'T_PRIVATE', 'T_PROTECTED', 'T_FINAL', 'T_ABSTRACT')),
                                    0 => array('atom'       => 'none',
                                               'token'      => 'T_ECHO'),
                                    1 => array('atom'       => 'yes', 
                                               'notAtom'    => array('Sequence', 'Arguments', 'Function', 
                                                                     'Ppp', 'Final', 'Abstract')),
                                    2 => array('filterOut' => array_merge(array('T_DOT', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 
                                                                           'T_EQUAL', 'T_QUESTION', 'T_OPEN_PARENTHESIS', 
                                                                           'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_COMMA', ),
                                                                           Addition::$operators, Multiplication::$operators, 
                                                                           Power::$operators, 
                                                                           Bitshift::$operators, Logical::$operators,
                                                                           Postplusplus::$operators, Comparison::$operators)) 
        );
        
        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'rank'        => array(1 => '0'),
                               'keepIndexed' => true);
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

        return false;
    }
}
?>
