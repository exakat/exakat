<?php

namespace Tokenizer;

class Variable extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_VARIABLE',
                                             'atom' => 'none')
                                  
        );
        
        $this->actions = array('atom'       => 'Variable');

        $r = $this->checkAuto();
        
        $this->conditions = array(-1 => array('token' => 'T_DOLLAR_OPEN_CURLY_BRACES',
                                             'atom' => 'none'),
                                  0 => array('token' => 'T_STRING_VARNAME'),
                                  1 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'transform' => array('-1' => 'DROP',
                                                     '1' => 'DROP'),
                                'atom'       => 'Variable');

        $r = $this->checkAuto();
        
        	
    }
}

?>