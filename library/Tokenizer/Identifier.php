<?php

namespace Tokenizer;

class Identifier extends TokenAuto {
    static public $atom = 'Identifier';

    public function _check() {
        return false;
    }

    public function fullcode() {
        return 'it.fullcode = it.code; ';
    }

}

?>