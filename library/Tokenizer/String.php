<?php

namespace Tokenizer;

class String extends TokenAuto {
    static public $operators = array('T_QUOTE');

    function _check() {
// Case of string with interpolation : "a${b}c";
        $this->conditions = array(  0 => array('token' => String::$operators, 
                                               'atom' => 'none'),
                                    1 => array('atom'  => array('String', 'Variable', 'Concatenation')),
                                    2 => array('token' => String::$operators, 
                                               'atom' => 'none')
                                 );
        
        $this->actions = array( 'transform' => array( 1 => 'CONTAIN',
                                                      2 => 'DROP'),
                                'atom'       => 'String',
                                'mergeNext'  => array('Concatenation' => 'CONCAT')
                                );
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>