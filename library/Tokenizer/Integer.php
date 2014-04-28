<?php

namespace Tokenizer;

class Integer extends TokenAuto {
    static public $atom = 'Integer';

    public function _check() {
        return false;
    }

    public function fullcode() {
        return 'it.fullcode = it.code ';
    }

}

?>