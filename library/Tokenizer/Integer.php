<?php

namespace Tokenizer;

class Integer extends TokenAuto {
    function _check() {
        return false;
    }

    function fullcode() {
        return 'it.fullcode = it.code ';
    }

}

?>