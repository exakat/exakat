<?php

namespace Tokenizer;

class RawString extends TokenAuto {
    function _check() {
        return false;
    }

    function fullcode() {
        return 'it.fullcode = it.code; ';
    }

}

?>