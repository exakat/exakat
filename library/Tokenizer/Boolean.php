<?php

namespace Tokenizer;

class Boolean extends TokenAuto {
    static public $operators = array();

    function _check() {
        $this->conditions = array( 0 => array('token' => 'T_STRING',
                                              'icode'  => array('true', 'false'),
                                               'atom' => 'none')
                                 );
        
        $this->actions = array('atom'       => 'Boolean');
        
        $this->checkAuto();
        return $this->checkRemaining();
    }
}

?>