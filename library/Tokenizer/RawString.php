<?php

namespace Tokenizer;

class RawString extends TokenAuto {
    static public $atom = 'Rawstring';

    public function _check() {
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.getProperty('code')); 

GREMLIN;
    }

}

?>