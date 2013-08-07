<?php

namespace Tokenizer;

class Constant extends TokenAuto {
    static public $operators = array('T_CONSTANT_ENCAPSED_STRING', 'T_STRING');

    function _check() {
        return 0;
    }
}

?>