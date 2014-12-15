<?php

namespace Tokenizer;

class Boolean extends TokenAuto {
    static public $operators = array();
    static public $atom = 'Boolean';
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode',  fullcode.getProperty('code'));

GREMLIN;
    }
}

?>
