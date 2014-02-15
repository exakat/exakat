<?php

namespace Tokenizer;

class Constant extends TokenAuto {
    static public $operators = array('T_CONSTANT_ENCAPSED_STRING', 'T_STRING');

    function _check() {
        // @note a\b\c as F
        $this->conditions = array( 0 => array('token' => 'T_STRING',
                                              'atom'  => 'Identifier'), 
                                   1 => array('token' => 'T_AS'),
                                   2 => array('atom' => 'Identifier'),
        );
        
        $this->actions = array('transform'   => array( 1 => 'DROP',
                                                       2 => 'AS' ),
                               'atom'        => 'Nsname',
                               'cleanIndex'  => true,
                               );
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>