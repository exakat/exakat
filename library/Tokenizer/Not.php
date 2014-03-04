<?php

namespace Tokenizer;

class Not extends TokenAuto {
    static public $operators = array('T_BANG', 'T_TILDE');

    public function _check() {
        $this->conditions = array(0 => array('token' => Not::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOT', 'T_DOUBLE_COLON',
                                                                  'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_NS_SEPARATOR', )),
        );
        
        $this->actions = array('makeEdge'   => array( 1 => 'NOT'),
                               'atom'       => 'Not',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = "!" + it.out("NOT").next().fullcode; ';
    }
}

?>