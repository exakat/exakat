<?php

namespace Tokenizer;

class Real extends TokenAuto {
    static public $atom = "Real";
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.getProperty('code'));

GREMLIN;
    }

}

?>
