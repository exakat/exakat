<?php

namespace Tokenizer;

class _Const extends TokenAuto {
    function _check() {
    
    // class x {}
        $this->conditions = array( 0 => array('token' => 'T_CONST'),
                                   1 => array('atom' => 'String'),
                                   2 => array('token' => 'T_EQUAL'),
                                   3 => array('atom' => array('String,', 'Integer', 'Staticconstant')),
                                 );
        
        $this->actions = array('transform'   => array(   1 => 'NAME',
                                                         2 => 'DROP',
                                                         3 => 'VALUE'),
                               'atom'       => 'Const',
                               );

        $r = $this->checkAuto(); 

    // class x { const a = 2; }
        $this->conditions = array( 0 => array('atom' => 'Const'),
                                   1 => array('token' => 'T_COMMA'),
                                   2 => array('atom' => 'String'),
                                   3 => array('token' => 'T_EQUAL'),
                                   4 => array('atom' => array('String,', 'Integer', 'Staticconstant')),
                                 );
        
        $this->actions = array('transform'   => array(   1 => 'TO_CONST' ),
                               'atom'       => 'Const',
                               );

        $r = $this->checkAuto(); 

        return $r;
    }
}
?>