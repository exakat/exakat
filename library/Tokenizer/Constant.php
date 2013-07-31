<?php

namespace Tokenizer;

class Constant extends TokenAuto {
    static public $operators = array('T_CONSTANT_ENCAPSED_STRING', 'T_STRING');
    function _check() {

        $this->conditions = array( 0 => array('token' => Constant::$operators,
                                               'atom' => 'none'),
                                   1 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
                                 );
        
        $this->actions = array('atom'       => 'Constant');
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>