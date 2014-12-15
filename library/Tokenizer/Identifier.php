<?php

namespace Tokenizer;

class Identifier extends TokenAuto {
    static public $atom = 'Identifier';

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.getProperty('code')); 

GREMLIN
;
    }

}

?>
