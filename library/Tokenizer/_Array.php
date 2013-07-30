<?php

namespace Tokenizer;

class _Array extends TokenAuto {
    static public $operators = array('T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_DOLLAR_OPEN_CURLY_BRACES');
    
    function _check() {
        $this->conditions = array( -1 => array('atom' => array('Variable', 'Array', 'Property', 'Staticproperty' )),
                                    0 => array('token' => _Array::$operators),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => array('T_CLOSE_BRACKET', 'T_CLOSE_CURLY')),
                                 );
        
        $this->actions = array('transform' => array(  -1 => 'VARIABLE', 
                                                       1 => 'INDEX',
                                                       2 => 'DROP'),
                               'atom'      => 'Array');
        $this->checkAuto(); 

        $this->conditions = array( 0 => array('token' => _Array::$operators),
                                   1 => array('token' => 'T_STRING_VARNAME'),
                                   2 => array('token' => 'T_OPEN_BRACKET'),
                                   3 => array('token' => 'T_CONSTANT_ENCAPSED_STRING'),
                                   4 => array('token' => 'T_CLOSE_BRACKET'),
                                   5 => array('token' => 'T_CLOSE_CURLY'),
                                 );
        
        $this->actions = array('transform' => array(   1 => 'VARIABLE',
                                                       2 => 'DROP',
                                                       3 => 'INDEX',
                                                       4 => 'DROP',
                                                       5 => 'DROP'  ),
                               'atom'      => 'Array');
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>