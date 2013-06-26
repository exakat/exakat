<?php

namespace Tokenizer;

class _Global extends TokenAuto {
    function _check() {
    
    // global $x;
        $this->conditions = array( 0 => array('token' => 'T_GLOBAL'),
                                   1 => array('atom' => array('Variable')),
                                 );
        
        $this->actions = array('transform' => array( 1 => 'GLOBAL'),
                               'atom'      => 'Global',
                               );

        $r = $this->checkAuto(); 

    // global $x, $y
        $this->conditions = array( 0 => array('token' => 'T_VAR'),
                                   1 => array('atom' => 'Arguments'),
                                 );
        
        $this->actions = array('to_global'   => true,
                               'atom'       => 'Global',
                               );

        $r = $this->checkAuto(); 

        return $r;
    }
}
?>