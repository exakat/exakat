<?php

namespace Tokenizer;

class _Return extends TokenAuto {
    static public $operators = array('T_RETURN');

    function _check() {
        $this->conditions = array( 0 => array('token' => _Return::$operators,
                                              'atom' => 'none' ),
                                   1 => array('token' => array('T_SEMICOLON'))
        );
        
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'CODE')),
                               'keepIndexed' => true);
        $this->checkAuto();

        $this->conditions = array( 0 => array('token' => _Return::$operators,
                                              'atom' => 'none' ),
                                   1 => array('atom' => 'yes'),
                                   2 => array('filterOut' => array_merge(array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_DOT'),
                                                                Addition::$operators, Multiplication::$operators, Bitshift::$operators, Comparison::$operators)),
        );
        
        $this->actions = array('makeEdge'   => array( 1 => 'RETURN'),
                               'atom'       => 'Return',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}
?>