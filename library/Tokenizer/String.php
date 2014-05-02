<?php

namespace Tokenizer;

class String extends TokenAuto {
    static public $operators = array('T_QUOTE'); 
    static public $allowed_classes = array('String', 'Variable', 'Concatenation', 'Array', 'Property', 'Methodcall', 
                                           'Staticmethodcall', 'Staticproperty', 'Staticconstant', 'Ternary');
    static public $atom = 'String';

    public function _check() {
// Case of string with interpolation : "a${b}c";
        $this->conditions = array(  0 => array('token'            => String::$operators, 
                                               'atom'             => 'none'),
                                    1 => array('atom'             => String::$allowed_classes,
                                               'check_for_string' => String::$allowed_classes),
                                 );

        $this->actions = array( 'make_quoted_string' => 'String');
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN
fullcode.setProperty('fullcode', fullcode.out('CONTAIN').next().fullcode);

if (fullcode.code.length() > 1) {
    if (fullcode.code.substring(0, 1) in ["'", '"']) {
        fullcode.setProperty("delimiter", fullcode.code.substring(0, 1));
        fullcode.setProperty("noDelimiter", fullcode.code.substring(1, fullcode.code.length() - 1));
    }

    // @note : only the first delimiter is removed, it is sufficient
//    fullcode.setProperty('unicode_block', fullcode.code.replaceAll(/^['"]/, '').toList().groupBy{ Character.UnicodeBlock.of( it as char ).toString() }.sort{-it.value.size}.find{true}.key.toString());
}

GREMLIN;
    }
}

?>