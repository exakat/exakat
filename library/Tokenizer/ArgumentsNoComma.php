<?php

namespace Tokenizer;

class Argumentsnocomma extends TokenAuto {
    static public $operators = array('T_OPEN_PARENTHESIS');

    function _check() {
        // @note f(1) : no comma 
        $this->conditions = array(-1 => array('token' => array_merge(Functioncall::$operators, array('T_DECLARE'))),
                                   0 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'atom'  => 'none'),
                                   1 => array('atom'  => Arguments::$operands_wa),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array_merge(array('T_DOUBLECOLON', 'T_OPEN_PARENTHESIS'), 
                                                                                Logical::$operators)),
        );

        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));
        $this->checkAuto();
        
        // @note f() : no argument
        $this->conditions = array(-2 => array('filterOut' => array('T_NS_SEPARATOR')),
                                  -1 => array('token' => array('T_STRING', 'T_ECHO', 'T_UNSET', 'T_EVAL', 'T_PRINT', 'T_ARRAY', 'T_VARIABLE', 'T_NS_SEPARATOR')),
                                   0 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON')),
        );
        
        $this->actions = array('addEdge'   => array(0 => array('Arguments' => 'ARGUMENT')),
                               'cleanIndex' => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }
}
?>