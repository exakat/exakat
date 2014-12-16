<?php

namespace Tokenizer;

class String extends TokenAuto {
    static public $operators = array('T_QUOTE'); 
    static public $allowedClasses = array('String', 'Variable', 'Concatenation', 'Array', 'Property', 'Methodcall', 
                                          'Staticmethodcall', 'Staticproperty', 'Staticconstant', 'Ternary', 'Concatenation',
                                          'Functioncall');
    static public $atom = 'String';

    public function _check() {
// Case of string with interpolation : "a${b}c";
        $this->conditions = array(  0 => array('token'            => String::$operators, 
                                               'atom'             => 'none'),
                                    1 => array('atom'             => String::$allowedClasses,
                                               'check_for_string' => String::$allowedClasses),
                                 );

        $this->actions = array( 'make_quoted_string' => 'String');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

s = [];
fullcode.out('CONTAIN').out('CONCAT').sort{it.rank}._().each{ s.add(it.fullcode); }
fullcode.setProperty('fullcode', '"' + s.join('') + '"');

if (fullcode.code.length() > 1) {
    if (fullcode.code.substring(0, 1) in ["'", '"']) {
    // @note : only the first delimiter is removed, it is sufficient
        fullcode.setProperty("delimiter", fullcode.code.substring(0, 1));
        fullcode.setProperty("noDelimiter", fullcode.code.substring(1, fullcode.code.length() - 1));
    }
}

GREMLIN;
    }
}

?>
