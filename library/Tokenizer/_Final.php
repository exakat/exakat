<?php

namespace Tokenizer;

class _Final extends TokenAuto {
    function _check() {
    
        $tokens = array('T_FINAL');

    // abstract class x { abstract function x() }
        $this->conditions = array( 0 => array('token' => 'T_FINAL'),
                                   1 => array('atom' => array('Function', 'Ppp', 'Static', 'Abstract', 'Class', )),
                                 );
        
        $this->actions = array('transform' => array( 1 => 'FINAL'),
                               'atom'      => 'Final',
                               );

        $r = $this->checkAuto(); 

        return $r;
    }
}
?>