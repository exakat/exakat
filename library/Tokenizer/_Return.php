<?php

namespace Tokenizer;

class _Return extends TokenAuto {
    function _check() {
        $this->conditions = array( 0 => array('token' => 'T_RETURN',
                                              'atom' => 'none' ),
                                   1 => array('atom' => 'yes'),
        );
        
        $this->actions = array('makeEdge' => array( '1' => 'RETURN'),
                               'atom'     => 'Return');
        return $this->checkAuto();
    }
}
?>