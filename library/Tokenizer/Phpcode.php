<?php

namespace Tokenizer;

class Phpcode extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_OPEN_TAG',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_CLOSE_TAG'),
                                  
                                  
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'CODE'),
                               'dropNext'   => array(1), 
                               'atom'       => 'Phpcode');
        $r = $this->checkAuto();
        return $r;
    }
}

?>