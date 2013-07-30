<?php

namespace Tokenizer;

class Not extends TokenAuto {
    static public $operators = array('T_BANG');

    function _check() {
        $this->conditions = array(0 => array('token' => Not::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOT', 'T_DOUBLE_COLON',
                                                                  'T_OPEN_BRACKET', 'T_OPEN_CURLY',)),
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'NOT'),
                               'atom'       => 'Not');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>