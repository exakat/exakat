<?php

namespace Tokenizer;

class String extends TokenAuto {
    static public $operators = array('T_QUOTE', 'T_START_HEREDOC');

    function _check() {
// Case of string with interpolation : "a${b}c";
        $this->conditions = array(  0 => array('token' => String::$operators, 
                                               'atom' => 'none'),
                                    1 => array('atom'  => array('String', 'Variable', 'Concatenation', 'Array')),
                                 );

        $this->actions = array( 'make_quoted_string' => 'String');
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>