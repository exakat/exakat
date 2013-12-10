<?php

namespace Tokenizer;

class Boolean extends TokenAuto {
    static public $operators = array();

    function _check() {
        return 0;
    }

    function fullcode() {
        return 'it.fullcode = it.code; ';
    }
}

?>