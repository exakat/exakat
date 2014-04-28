<?php

namespace Tokenizer;

class Real extends TokenAuto {
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