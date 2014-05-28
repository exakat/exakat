<?php

namespace Tokenizer;

class Void extends TokenAuto {
    static public $operators = array('T_OPEN_PARENTHESIS', 'T_SEMICOLON');

    public function _check() {
    // needed for for(;;)
        return true;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', ''); 

GREMLIN;
    }
}
?>