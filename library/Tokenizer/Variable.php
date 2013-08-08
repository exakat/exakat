<?php

namespace Tokenizer;

class Variable extends TokenAuto {
    static public $operators = array('T_DOLLAR_OPEN_CURLY_BRACES', 'T_CURLY_OPEN');
    
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_DOLLAR_OPEN_CURLY_BRACES',
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_STRING_VARNAME'),
                                  2 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'transform' => array( 1 => 'NAME',
                                                      2 => 'DROP'),
                                'atom'       => 'Variable',
                                'cleanIndex' => true);
        $this->checkAuto();

        $this->conditions = array(0 => array('token' => array('T_CURLY_OPEN', 'T_DOLLAR_OPEN_CURLY_BRACES'),
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'transform' => array(1 => 'NAME',
                                                     2 => 'DROP'),
                                'atom'       => 'Variable',
                                'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>