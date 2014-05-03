<?php

namespace Tokenizer;

class Integer extends TokenAuto {
    static public $atom = 'Integer';

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.getProperty('code')); 

GREMLIN;
    }

}

?>