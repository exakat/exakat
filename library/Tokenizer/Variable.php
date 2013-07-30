<?php

namespace Tokenizer;

class Variable extends TokenAuto {
    static public $operators = array('T_DOLLAR_OPEN_CURLY_BRACES', 'T_CURLY_OPEN', 'T_DOLLAR_OPEN_CURLY_BRACES',
                                     'T_DOLLAR', );
    
    function _check() {
        $this->conditions = array(-1 => array('token' => 'T_DOLLAR_OPEN_CURLY_BRACES',
                                             'atom' => 'none'),
                                  0 => array('token' => 'T_STRING_VARNAME'),
                                  1 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'transform' => array('-1' => 'DROP',
                                                     '1' => 'DROP'),
                                'atom'       => 'Variable');

        $r = $this->checkAuto();

        $this->conditions = array(-1 => array('token' => array('T_CURLY_OPEN', 'T_DOLLAR_OPEN_CURLY_BRACES'),
                                             'atom' => 'none'),
                                  0 => array('atom' => 'yes'),
                                  1 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'transform' => array(-1 => 'DROP',
                                                      1 => 'DROP'),
                                'atom'       => 'Variable');
        $r = $this->checkAuto();

        $this->conditions = array(0 => array('token' => 'T_DOLLAR',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('filterOut' => array('T_OPEN_BRACKET')),
        );
        
        $this->actions = array( 'transform' => array('1' => 'NAME'),
                                'atom'      => 'Variable');

        $r = $this->checkAuto();

        $this->conditions = array(0 => array('token' => 'T_DOLLAR',
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_OPEN_CURLY'),
                                  2 => array('atom' => 'yes'),
                                  3 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'transform' => array(1 => 'DROP',
                                                     2 => 'NAME',
                                                     3 => 'DROP'),
                                'atom'       => 'Variable');
        $r = $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>