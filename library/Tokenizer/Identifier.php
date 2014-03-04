<?php

namespace Tokenizer;

class Identifier extends TokenAuto {
    public function _check() {
        return false;
    }

    public function fullcode() {
        return 'it.fullcode = it.code; ';
    }

}

?>