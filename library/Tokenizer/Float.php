<?php

namespace Tokenizer;

class Float extends TokenAuto {
    public function _check() {

        $this->conditions = array( 0 => array('token' => 'T_DNUMBER',
                                               'atom' => 'none')
                                 );
        
        $this->actions = array('atom'       => 'Float',
                               );
        
        return $this->checkAuto();
    }

    public function fullcode() {
        return 'it.fullcode = it.code; ';
    }
}

?>