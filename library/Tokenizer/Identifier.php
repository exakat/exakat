<?php

namespace Tokenizer;

class Identifier extends TokenAuto {
    function _check() {
        return false;
    }

    function fullcode() {
        return 'it.fullcode = it.code; ';
    }

}

?>