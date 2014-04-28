<?php

namespace Tokenizer;

class Constant extends TokenAuto {
    static public $operators = array('T_CONSTANT_ENCAPSED_STRING', 'T_STRING');
    static public $atom = 'Constant';

    public function _check() {
        return 0;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.getProperty('code'));

GREMLIN;
    }

}

?>