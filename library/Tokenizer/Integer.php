<?php

namespace Tokenizer;

class Integer extends TokenAuto {
    public function _check() {
        return false;
    }

    public function fullcode() {
        return 'it.fullcode = it.code ';
    }

}

?>