<?php

namespace Tokenizer;

class String extends TokenAuto {
    function _check() {

        $this->conditions = array( 0 => array('token' => array('T_STRING', 'T_CONSTANT_ENCAPSED_STRING', 'T_CONSTANT_ENCAPSED_STRING', 'T_ENCAPSED_AND_WHITESPACE'),
                                               'atom' => 'none')
                                 );
        
        $this->actions = array('atom'       => 'String',
                               );
        
        $r =  $this->checkAuto();

        return $this->checkAuto();
    }
}

?>