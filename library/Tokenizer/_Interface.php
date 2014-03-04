<?php

namespace Tokenizer;

class _Interface extends TokenAuto {
    static public $operators = array('T_INTERFACE');

    public function _check() {
        $this->conditions = array(0 => array('token' => _Interface::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Identifier'),
                                  2 => array('atom' => 'Block'),
        );
        
        $this->actions = array('transform'  => array( 1 => 'NAME',
                                                      2 => 'BLOCK'),
                               'atom'       => 'Interface',
                               'cleanIndex' => true);
        $this->checkAuto();

        $this->conditions = array(0 => array('token' => _Interface::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Identifier'),
                                  2 => array('token' => 'T_EXTENDS'),
                                  3 => array('atom' => array('Arguments', 'Identifier', 'Nsname')),
                                  4 => array('atom' => 'Block'),
        );
        
        $this->actions = array('transform'  => array( 1 => 'NAME',
                                                      2 => 'DROP',
                                                      3 => 'EXTENDS',
                                                      4 => 'BLOCK'),
                               'atom'       => 'Interface',
                               'arguments2extends' => true,
                               'cleanIndex' => true);
        $this->checkAuto();

        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = "interface " + it.out("NAME").next().code; 
current = it;

// extends
it.out("EXTENDS").each{ current.fullcode = current.fullcode + " extends " + it.fullcode;}
        
        ';
    }

}

?>