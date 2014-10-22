<?php

namespace Tokenizer;

class _Insteadof extends TokenAuto {
    static public $operators = array('T_INSTEADOF');
    static public $atom = 'Insteadof';
    
    protected $phpversion = "5.4+";

    public function _check() {
        $this->conditions = array( -1 => array('atom'  => 'Staticconstant'), 
                                    0 => array('token' => _Insteadof::$operators,
                                               'atom'  => 'none'),
                                    1 => array('token' => 'T_STRING')
        );
        
        $this->actions = array('makeEdge'     => array(  1 => 'RIGHT',
                                                        -1 => 'LEFT'),
                               'atom'         => 'Instanceof',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it' );
        $this->checkAuto();
        
        return false;
    } 
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = fullcode.out("LEFT").next().code + " insteadof " + fullcode.out("RIGHT").next().code;

GREMLIN;
    }
}
?>