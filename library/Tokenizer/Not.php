<?php

namespace Tokenizer;

class Not extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_BANG',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'NOT'),
                               'atom'       => 'Not');
                               
        return $this->checkAuto();
    }
}

?>