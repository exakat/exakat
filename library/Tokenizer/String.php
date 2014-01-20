<?php

namespace Tokenizer;

class String extends TokenAuto {
    static public $operators = array('T_QUOTE', 'T_START_HEREDOC');
    static public $allowed_classes = array('String', 'Variable', 'Concatenation', 'Array', 'Property', 'Methodcall', 
                                           'Staticmethodcall', 'Staticproperty', 'Staticconstant', 'Ternary', );

    function _check() {
// Case of string with interpolation : "a${b}c";
        $this->conditions = array(  0 => array('token' => String::$operators, 
                                               'atom' => 'none'),
                                    1 => array('atom'  => String::$allowed_classes,
                                               'check_for_string' => String::$allowed_classes),
                                 );

        $this->actions = array( 'make_quoted_string' => 'String');
        $this->checkAuto();

        return $this->checkRemaining();
    }

    function fullcode() {
        return <<<GREMLIN
it.setProperty("delimiter", it.code.substring(0, 1));
it.fullcode = it.code;

GREMLIN;
    }
}

?>