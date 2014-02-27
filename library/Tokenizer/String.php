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
fullcode.fullcode = fullcode.code;

if (fullcode.code.length() > 1) {
    if (fullcode.code.substring(0, 1) in ["'", '"']) {
        fullcode.setProperty("delimiter", fullcode.code.substring(0, 1));
        fullcode.setProperty("noDelimiter", fullcode.code.substring(1, fullcode.code.length() - 1));
    }
    // @note : only the first delimiter is removed, it is sufficients
    fullcode.setProperty('unicode_block', fullcode.code.replaceAll(/^['"]/, '').toList().groupBy{ Character.UnicodeBlock.of( it as char ).toString() }.sort{-it.value.size}.find{true}.key.toString());
}

GREMLIN;
    }
}

?>