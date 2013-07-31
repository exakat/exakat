<?php

namespace Tokenizer;

class Void extends TokenAuto {
    function _check() {
    // needed for for(;;)
    
        $this->conditions = array(0 => array('token' => array('T_OPEN_PARENTHESIS', 'T_SEMICOLON'),
                                             'atom' => 'none'),
                                  1 => array('token' => array('T_CLOSE_PARENTHESIS', 'T_SEMICOLON'),
                                             'atom' => 'none'),
        );
        
        $this->actions = array('addEdge'    => array(0 => array('Void' => 'BLOCK')));
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}
?>