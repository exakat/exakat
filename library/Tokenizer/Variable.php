<?php

namespace Tokenizer;

class Variable extends TokenAuto {
    function check() {
        $this->conditions = array(0 => array('token' => 'T_VARIABLE',
                                             'atom' => 'none')
                                  
        );
        
        $this->actions = array('atom'       => 'Variable');

        return $this->checkAuto();
    }
}

?>