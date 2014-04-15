<?php

namespace Tokenizer;

class RawString extends TokenAuto {
    public function _check() {
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = fullcode.code; 
GREMLIN;
    }

}

?>