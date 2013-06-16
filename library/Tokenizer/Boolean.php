<?php

namespace Tokenizer;

class Boolean extends TokenAuto {
    function _check() {

        $this->conditions = array( 0 => array('token' => 'T_STRING',
                                              'icode'  => array('true', 'false'),
                                               'atom' => 'none')
                                 );
        
        $this->actions = array('atom'       => 'Boolean',
                               );
        
        return $this->checkAuto();
    }
}

?>