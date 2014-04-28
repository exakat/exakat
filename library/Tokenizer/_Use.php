<?php

namespace Tokenizer;

class _Use extends TokenAuto {
    static public $operators = array('T_USE');
    static public $atom = 'Use';

    public function _check() {
    // use \a\b;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom'  => array('Nsname', 'Identifier')),
                                   2 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'  => 'none'),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'USE'),
                               'atom'       => 'Use',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

    // use \a\b as C;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom'  => array('Nsname', 'Identifier')),
                                   2 => array('token' => 'T_AS'),
                                   3 => array('atom'  => 'Identifier'),
                                   4 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'  => 'none'),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'USE',
                                                      2 => 'DROP',
                                                      3 => 'AS'),
                               'atom'       => 'Use',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

    // use \b\c, \a\c;
        $this->conditions = array( 0 => array('token'    => _Use::$operators),
                                   1 => array('atom'     => 'Arguments'),
                                   2 => array('token'    => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'     => 'none'),
                                 );
        
        $this->actions = array('to_use'   => true,
                               'atom'     => 'Use' );
        $this->checkAuto(); 

    // use A {};
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom'  => array('Nsname', 'Identifier')),
                                   2 => array('atom'  => 'Sequence'),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'USE',
                                                      2 => 'BLOCK'),
                               'atom'       => 'Use',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

    // use A,B {};
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom'  => 'Arguments'),
                                   2 => array('atom'  => 'Sequence'),
                                 );
        
        $this->actions = array('to_use_block' => true,
                               'atom'         => 'Use',
                               'cleanIndex'   => true );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

s = [];
fullcode.out("USE").sort{it.order}._().each{ 
    a = it.fullcode;
    it.out('AS').each{
        a = a + ' as ' + it.code;
    }
    s.add(a); 
};

fullcode.setProperty('fullcode', fullcode.getProperty('code') + " " + s.join(", "));

GREMLIN;
    }
}
?>