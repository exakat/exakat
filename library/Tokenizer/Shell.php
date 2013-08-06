<?php

namespace Tokenizer;

class Shell extends TokenAuto {
    static public $operators = array('T_SHELL_QUOTE');

    function _check() {
// Case of string with interpolation : `a${b}c`;
        $this->conditions = array(  0 => array('token' => Shell::$operators, 
                                               'atom' => 'none'),
                                    1 => array('atom'  => array('String', 'Variable', 'Concatenation', 'Array')),
                                    2 => array('token' => 'T_SHELL_QUOTE_CLOSE', 
                                               'atom' => 'none')
                                 );
        
        $this->actions = array( 'transform' => array( 1 => 'CONTAIN',
                                                      2 => 'DROP'),
                                'atom'       => 'String',
                                'mergeNext'  => array('Concatenation' => 'CONCAT'),
                                'cleanIndex' => true,
                                );
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>