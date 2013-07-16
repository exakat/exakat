<?php

namespace Tokenizer;

class _Abstract extends TokenAuto {
    function _check() {
    
        $tokens = array('T_ABSTRACT');

    // abstract class x { abstract function x() }
        $this->conditions = array( 0 => array('token' => 'T_ABSTRACT'),
                                   1 => array('atom' => array('Class', 'Function', 'Ppp', 'Static', )),
                                 );
        
        $this->actions = array('transform' => array( 1 => 'ABSTRACT'),
                               'atom'      => 'Abstract',
                               );

        $r = $this->checkAuto(); 

        return $r;
    }
}
?>