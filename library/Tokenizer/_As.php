<?php

namespace Tokenizer;

class _As extends TokenAuto {
    static public $operators = array('T_AS');
    static public $atom = 'As';

    public function _check() {
        // use C::Const as string
        $this->conditions = array( -1 => array('atom'  => 'Staticconstant'), 
                                    0 => array('token' => _As::$operators,
                                               'atom'  => 'none'),
                                    1 => array('token' => 'T_STRING')
        );
        
        $this->actions = array('makeEdge'     => array( 1 => 'RIGHT',
                                                       -1 => 'LEFT'),
                               'atom'         => 'As',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it' );
        $this->checkAuto();
        
        // use A as B, use \A\B as C
        $this->conditions = array( -2 => array('notToken' => 'T_NS_SEPARATOR'),
                                   -1 => array('atom'     => array('Namespace', 'Identifier')), 
                                    0 => array('token'    => _As::$operators,
                                               'atom'     => 'none'),
                                    1 => array('token'    => 'T_STRING')
        );
        
        $this->actions = array('makeEdge'     => array( 1 => 'AS',
                                                       -1 => 'SUBNAME'),
                               'atom'         => 'As',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it' );
        $this->checkAuto();
        
        return $this->checkRemaining();
    } 
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("SUBNAME").next().getProperty('fullcode') + " as " + fullcode.out("AS").next().getProperty('fullcode'));

GREMLIN;
    }
}
?>