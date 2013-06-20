<?php

namespace Tokenizer;

class Staticconstant extends TokenAuto {
    function _check() {
        
        $operands = array('Constant');
        $this->conditions = array( -1 => array('atom' => $operands), 
                                    0 => array('token' => 'T_DOUBLE_COLON'),
                                    1 => array('atom' => array('Constant')),
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'CLASS',
                                                       1 => 'CONSTANT'),
                               'atom'       => 'Staticconstant',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }

    function reserve() {
        Token::$reserved[] = 'T_DOUBLE_COLON';
    }
}

?>