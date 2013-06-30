<?php

namespace Tokenizer;

class _Return extends TokenAuto {
    function _check() {
        $this->conditions = array( 0 => array('token' => 'T_RETURN',
                                              'atom' => 'none' ),
                                   1 => array('token' => array('T_SEMICOLON'))
        );
        
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'CODE')));
        $r = $this->checkAuto();

        $this->conditions = array( 0 => array('token' => 'T_RETURN',
                                              'atom' => 'none' ),
                                   1 => array('atom' => 'yes'),
                                   2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', )),
        );
        
        $this->actions = array('makeEdge' => array( '1' => 'RETURN'),
                               'atom'     => 'Return');

        return $this->checkAuto();
    }
}
?>