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
it.fullcode = it.code;

if (it.code.length() > 1) {
    if (it.code.substring(0, 1) in ["'", '"']) {
        it.setProperty("delimiter", it.code.substring(0, 1));
    }
    // @note : only the first delimiter is removed, it is sufficients
    it.setProperty('unicode_block', it.code.replaceAll(/^['"]/, '').toList().groupBy{ Character.UnicodeBlock.of( it as char ).toString() }.sort{-it.value.size}.find{true}.key.toString());
}

GREMLIN;
    }
}

?>