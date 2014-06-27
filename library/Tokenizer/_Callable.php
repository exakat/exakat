<?php

namespace Tokenizer;

class _Callable extends TokenAuto {
    static public $operators = array('T_CALLABLE'); 
    static public $atom = 'Identifier';

    public function _check() {  return 0;  }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', 'Callalble');

GREMLIN;
    }
}

?>