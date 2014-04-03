<?php

namespace Tokenizer;

class Halt extends TokenAuto {
    static public $operators = array('T_HALT_COMPILER');

    public function _check() {
        $this->conditions = array(0 => array('token' => Halt::$operators,
                                             'atom' => 'none'));
        
        $this->actions = array('atom'         => 'Halt',
//                               'makeSequence' => 'it'
                               );
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = fullcode.code;
GREMLIN;
    }
}

?>