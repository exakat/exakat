<?php

namespace Tokenizer;

class String extends TokenAuto {
    function _check() {

        $this->conditions = array( 0 => array('token' => array('T_STRING', 'T_CONSTANT_ENCAPSED_STRING', 'T_CONSTANT_ENCAPSED_STRING', 'T_ENCAPSED_AND_WHITESPACE'),
                                               'atom' => 'none')
                                 );
        
        $this->actions = array('atom'       => 'String',);
        
        $r =  $this->checkAuto();

// Case of string with interpolation : "a${b}c";
        $this->conditions = array(  0 => array('token' => array('T_QUOTE'), 'atom' => 'none'),
                                    1 => array('atom'  => 'yes'),
                                    2 => array('token' => array('T_QUOTE'), 'atom' => 'none')
                                 );
        
        $this->actions = array( 'transform' => array( 1 => 'CONTAIN',
                                                      2 => 'DROP'),
                                'atom'       => 'String',
                                'mergeNext'  => array('Concatenation' => 'CONCAT'), 
                                );
        $r =  $this->checkAuto();

        return $this->checkAuto();
    }
}

?>