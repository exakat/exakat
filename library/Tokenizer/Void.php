<?php

namespace Tokenizer;

class Void extends TokenAuto {
    // @todo move this to load 
    
    static public $operators = array('T_OPEN_PARENTHESIS', 'T_SEMICOLON');
    public function _check() {
    // needed for for(;;)
    
        $this->conditions = array(0 => array('token' => Void::$operators),
                                  1 => array('token' => array('T_CLOSE_PARENTHESIS', 'T_SEMICOLON'),
                                             'atom' => 'none'),
        );
        
        $this->actions = array('addEdge'     => array(0 => array('Void' => 'BLOCK')),
                               'keepIndexed' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', ''); 

GREMLIN;
    }
}
?>