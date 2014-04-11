<?php

namespace Tokenizer;

class Noscream extends TokenAuto {
    static public $operators = array('T_AT');
    
    public function _check() {
        $this->conditions = array(0 => array('token' => Noscream::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_OPEN_BRACKET', 'T_OPEN_PARENTHESIS'))
        );
        
        $this->actions = array('makeEdge'     => array( '1' => 'AT'),
                               'atom'         => 'Noscream',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = "@" + it.out("AT").next().fullcode; ';
    }
}

?>