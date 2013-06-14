<?php

namespace Tokenizer;

class Object extends TokenAuto {
    function _check() {
        $this->conditions = array( -1 => array('atom' => 'Variable'), 
                                    0 => array('code' => '->'),
                                    1 => array('atom' => 'none')
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'OBJECT',
                                                       1 => 'PROPERTY'),
                               'atom'       => 'Object',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }
}

?>