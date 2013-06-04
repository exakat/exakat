<?php

namespace Tokenizer;

class String extends TokenAuto {
    function check() {

        $this->conditions = array( 0 => array('token' => array('T_CONSTANT_ENCAPSED_STRING', 'T_CONSTANT_ENCAPSED_STRING'),
                                               'atom' => 'none')
                                 );
        
        $this->actions = array('atom'       => 'String',
                               );
        
        return $this->checkAuto();
    }
}

?>