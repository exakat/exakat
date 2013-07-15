<?php

namespace Tokenizer;

class Staticmethodcall extends TokenAuto {
    function _check() {
        
        $operands = array('Constant', 'String', 'Variable');
        $this->conditions = array( -1 => array('atom' => $operands), 
                                    0 => array('token' => 'T_DOUBLE_COLON'),
                                    1 => array('atom' => 'Functioncall'),
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'CLASS',
                                                       1 => 'METHOD'),
                               'atom'       => 'Staticmethodcall',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }
}

?>