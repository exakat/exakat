<?php

namespace Tokenizer;

class _Return extends TokenAuto {
    static public $operators = array('T_RETURN');

    public function _check() {
        // return ; 
        $this->conditions = array( 0 => array('token' => _Return::$operators,
                                              'atom' => 'none' ),
                                   1 => array('token' => array('T_SEMICOLON'))
        );
        
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'CODE')),
                               'keepIndexed' => true);
        $this->checkAuto();

        // return with something ;
        $this->conditions = array( 0 => array('token' => _Return::$operators,
                                              'atom' => 'none' ),
                                   1 => array('atom' => 'yes'),
                                   2 => array('filterOut' => Token::$instruction_ending),
        );
        
        $this->actions = array('makeEdge'   => array( 1 => 'RETURN'),
                               'atom'       => 'Return',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = it.code + " " + it.out("RETURN").next().fullcode; ';
    }
}
?>