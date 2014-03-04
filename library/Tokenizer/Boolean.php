<?php

namespace Tokenizer;

class Boolean extends TokenAuto {
    static public $operators = array();

    public function _check() {
        return 0;
    }

    public function fullcode() {
        return 'it.fullcode = it.code; ';
    }
}

?>