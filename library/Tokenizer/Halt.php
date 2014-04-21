<?php

namespace Tokenizer;

class Halt extends TokenAuto {
    static public $operators = array('T_HALT_COMPILER');

    public function _check() {
        $this->conditions = array(0 => array('token' => Halt::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  =>  array('Arguments', 'Void')),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  );
        
        $this->actions = array('makeEdge'     => array(2 => 'ARGUMENTS'),
                               'dropNext'     => array(1),
                               'atom'         => 'Halt',
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.code + "()");
GREMLIN;
    }
}

?>