<?php

namespace Tokenizer;

class _Use extends TokenAuto {
    static public $operators = array('T_USE');

    public function _check() {
    // use \a\b;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom'  => array('Nsname', 'Identifier')),
                                   2 => array('token' => 'T_SEMICOLON',
                                              'atom'  => 'none'),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'USE'),
                               'atom'       => 'Use',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

    // use \b\c, \a\c;
        $this->conditions = array( 0 => array('token'    => _Use::$operators),
                                   1 => array('atom'     => 'Arguments'),
                                   2 => array('notToken' => 'T_COMMA'),
                                   3 => array('token'    => 'T_SEMICOLON',
                                              'atom'     => 'none'),
                                 );
        
        $this->actions = array('to_use'   => true,
                               'atom'     => 'Use' );
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
fullcode.fullcode = "use " + fullcode.out("USE").next().code;

GREMLIN;
    }
}
?>