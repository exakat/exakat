<?php

namespace Tokenizer;

class _Use extends TokenAuto {
    static public $operators = array('T_USE');

    function _check() {
    // use \a\b;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom' => array('Nsname', 'Identifier')),
                                   2 => array('token' => 'T_SEMICOLON'),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'USE'),
                               'atom'       => 'Use',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

    // use \b\c, \a\c;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_COMMA')),
                                 );
        
        $this->actions = array('to_use'   => true,
                               'atom'     => 'Use' );
        $this->checkAuto(); 

    // use \b\c as d;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom'  => array('Nsname', 'Identifier')),
                                   2 => array('token' => 'T_AS'),
                                   3 => array('atom' => array('Identifier')),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'USE',
                                                      2 => 'DROP',
                                                      3 => 'AS'),
                               'atom'       => 'Use',
                               'cleanIndex' => true );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>