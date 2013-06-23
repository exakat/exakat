<?php

namespace Tokenizer;

class _Array extends TokenAuto {
    function _check() {
        $this->conditions = array( -1 => array('atom' => array('Variable', 'Array', 'Property' )),
                                   0 => array('token' => array('T_OPEN_BRACKET', 'T_OPEN_CURLY')),
                                   1 => array('atom' => 'yes'),
                                   2 => array('token' => array('T_CLOSE_BRACKET', 'T_CLOSE_CURLY')),
                                 );
        
        $this->actions = array('transform'   => array(  -1 => 'VARIABLE', 
                                                         1 => 'INDEX',
                                                         2 => 'DROP'),
                               'atom'       => 'Array',
                               );

        $r = $this->checkAuto(); 

        $this->conditions = array( 0 => array('token' => array('T_DOLLAR_OPEN_CURLY_BRACES')),
                                   1 => array('token' => 'T_STRING_VARNAME'),
                                   2 => array('token' => 'T_OPEN_BRACKET'),
                                   3 => array('token' => 'T_CONSTANT_ENCAPSED_STRING'),
                                   4 => array('token' => 'T_CLOSE_BRACKET'),
                                   5 => array('token' => 'T_CLOSE_CURLY'),
                                 );
        
        $this->actions = array('transform'   => array(   1 => 'VARIABLE',
                                                         2 => 'DROP',
                                                         3 => 'INDEX',
                                                         4 => 'DROP',
                                                         5 => 'DROP'
                                                         ),
                               'atom'       => 'Array',
                               );

        $r = $this->checkAuto(); 

        return $r;
    }

    
    function reserve() {
        Token::$reserved[] = 'T_OPEN_BRACKET';
        
        return true;
    }
}

?>