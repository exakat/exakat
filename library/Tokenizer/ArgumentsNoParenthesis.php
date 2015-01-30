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
                                               'token'      => array('T_REQUIRE', 'T_REQUIRE_ONCE', 'T_INCLUDE_ONCE', 'T_INCLUDE',
                                                                     'T_PRINT', 'T_EXIT', 'T_ECHO') ),
                                    1 => array('atom'       => 'yes',
                                               'notAtom'    => 'Arguments'),
                                    2 => array('token'      => array('T_SEMICOLON', 'T_CLOSE_TAG', 'T_ENDIF', 'T_ENDSWITCH', 'T_ENDFOR',
                                                                     'T_ENDFOREACH', 'T_COMMA', 'T_CLOSE_PARENTHESIS', 'T_QUESTION', 'T_COLON',
                                                                     'T_ELSEIF', 'T_ECHO'))
        );
        
        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'rank'        => array(1 => '0'),
                               'keepIndexed' => true);
        $this->checkAuto();

        // @note exit; no parenthesis, no argument.
        $this->conditions = array( -1 => array('notToken' => 'T_INSTANCEOF'),
                                    0 => array('atom' => 'none',
                                               'token' => array('T_EXIT', 'T_STATIC')),
                                    1 => array('token'  => 'T_SEMICOLON')
        );
        
        $this->actions = array('addEdge'     => array(0 => array('Arguments' => 'ARGUMENT')),
                               'keepIndexed' => true);
        $this->checkAuto();

        return false;
    }
}
?>
