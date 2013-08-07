<?php

namespace Tokenizer;

class Functioncall extends TokenAuto {
    static $operators = array('T_STRING', 'T_ECHO', 'T_UNSET', 'T_EMPTY', 'T_ARRAY', 'T_NS_SEPARATOR', 
                              'T_VARIABLE', 'T_PRINT', 'T_ISSET', 'T_LIST', 'T_EVAL', 
                              'T_EXIT', 'T_DIE');

    function _check() {
        
        // functioncall(with arguments or void)
        $this->conditions = array(  -2 => array('filterOut' => array('T_FUNCTION')),
                                    -1 => array('filterOut' => array('T_FUNCTION', 'T_NS_SEPARATOR')),
                                     0 => array('token' => Functioncall::$operators),
                                     1 => array('atom'  => 'none',
                                                'token' => 'T_OPEN_PARENTHESIS' ),
                                     2 => array('atom'  =>  array('Arguments', 'Void')),
                                     3 => array('atom'  => 'none',
                                                'token' => 'T_CLOSE_PARENTHESIS'),
        );
        
        $this->actions = array('makeEdge'   => array(2 => 'ARGUMENTS'),
                               'dropNext'   => array(1),
                               'atom'       => 'Functioncall');
                               
        $this->checkAuto();

        // functioncall(with arguments)
        $this->conditions = array( 0 => array('token' => array('T_ECHO', 'T_PRINT', 'T_EXIT'),
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Arguments'),
                                   2 => array('filterOut2' => array_merge( array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON', 'T_COMMA', 'T_QUESTION'),
                                                                           Addition::$operators, Multiplication::$operators)),
        );
        
        $this->actions = array('makeEdge'   => array('1' => 'ARGUMENTS'),
                               'atom'       => 'Functioncall');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}
?>