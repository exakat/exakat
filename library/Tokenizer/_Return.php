<?php

namespace Tokenizer;

class _Return extends TokenAuto {
    static public $operators = array('T_RETURN');
    static public $atom = 'Return';

    public function _check() {
        // return ; 
        $this->conditions = array( 0 => array('token' => _Return::$operators,
                                              'atom' => 'none' ),
                                   1 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG', 'T_ENDIF'))
        );
        
        $this->actions = array('addEdge'     => array(0 => array('Void' => 'CODE')),
                               'keepIndexed' => true);
        $this->checkAuto();

        // return with something ;
        $this->conditions = array( 0 => array('token' => _Return::$operators,
                                              'atom' => 'none' ),
                                   1 => array('atom' => 'yes'),
                                   2 => array('filterOut2' => Token::$instruction_ending),
        );
        
        $this->actions = array('makeEdge'     => array( 1 => 'RETURN'),
                               'atom'         => 'Return',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.getProperty('code') + " " + fullcode.out("RETURN").next().getProperty('fullcode'));

GREMLIN;
    }
}
?>