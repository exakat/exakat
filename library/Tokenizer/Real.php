<?php

namespace Tokenizer;

class Real extends TokenAuto {
    function _check() {
        return false;
    }

    function fullcode() {
        return 'it.fullcode = it.code; ';
    }

}

?>