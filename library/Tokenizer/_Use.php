<?php

namespace Tokenizer;

class _Use extends TokenAuto {
    static public $operators = array('T_USE');

    function _check() {
    // use \a\b;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom' => 'Nsname'),
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

        return $this->checkRemaining();
    }
}
?>