<?php

namespace Tokenizer;

class Methodcall extends TokenAuto {
    function _check() {
        
        $operands = array('Variable', 'Property', '_Array');
        $this->conditions = array( -1 => array('atom' => $operands), 
                                    0 => array('code' => '->'),
                                    1 => array('atom' => 'Functioncall')
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'OBJECT',
                                                       1 => 'METHOD'),
                               'atom'       => 'Methodcall',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }
}

?>