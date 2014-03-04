<?php

namespace Tokenizer;

class Real extends TokenAuto {
    public function _check() {
        return false;
    }

    public function fullcode() {
        return 'it.fullcode = it.code; ';
    }

}

?>