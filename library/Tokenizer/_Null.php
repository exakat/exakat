<?php

namespace Tokenizer;

class _Null extends TokenAuto {
    static public $operators = array();
    static public $atom = 'Null';
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode',  fullcode.getProperty('code'));

GREMLIN;
    }
}

?>
