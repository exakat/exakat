<?php

namespace Tokenizer;

class String extends TokenAuto {
    static public $operators = array('T_QUOTE', 'T_START_HEREDOC');

    function _check() {
// Case of string with interpolation : "a${b}c";
        $allowed_classes = array('String', 'Variable', 'Concatenation', 'Array', 'Property', 'Methodcall' );
        $this->conditions = array(  0 => array('token' => String::$operators, 
                                               'atom' => 'none'),
                                    1 => array('atom'  => $allowed_classes,
                                               'check_for_string' => $allowed_classes),
                                 );

        $this->actions = array( 'make_quoted_string' => 'String');
        $this->checkAuto();

        return $this->checkRemaining();
    }

    function fullcode() {
        return 'it.fullcode = it.code; ';
    }
}

?>