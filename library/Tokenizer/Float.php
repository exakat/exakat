<?php

namespace Tokenizer;

class Float extends TokenAuto {
    function _check() {

        $this->conditions = array( 0 => array('token' => 'T_DNUMBER',
                                               'atom' => 'none')
                                 );
        
        $this->actions = array('atom'       => 'Float',
                               );
        
        return $this->checkAuto();
    }
}

?>