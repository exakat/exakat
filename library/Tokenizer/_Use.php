<?php

namespace Tokenizer;

class _Use extends TokenAuto {
    static public $operators = array('T_USE');
    static public $atom = 'Use';

    public function _check() {
    // use \a\b;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom'  => array('Nsname', 'Identifier', 'As')),
                                   2 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'  => 'none'),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'USE'),
                               'atom'       => 'Use',
                               'cleanIndex' => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto(); 

    // use \b\c, \a\c;
        $this->conditions = array( 0 => array('token'    => _Use::$operators),
                                   1 => array('atom'     => 'Arguments'),
                                   2 => array('token'    => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'     => 'none'),
                                 );
        
        $this->actions = array('to_use'       => true,
                               'atom'         => 'Use',
                               'makeSequence' => 'it' );
        $this->checkAuto(); 

    // use A {};
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom'  => array('Nsname', 'Identifier')),
                                   2 => array('atom'  => 'Sequence'),
                                 );
        
        $this->actions = array('transform'  => array( 1 => 'USE',
                                                      2 => 'BLOCK'),
                               'atom'       => 'Use',
                               'cleanIndex' => true,
                               'makeSequence' => 'it' 
                               );
        $this->checkAuto(); 

    // use A,B {};
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('atom'  => 'Arguments'),
                                   2 => array('atom'  => 'Sequence'),
                                 );
        
        $this->actions = array('to_use_block' => true,
                               'atom'         => 'Use',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'  );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

s = [];
fullcode.out("USE").sort{it.order}._().each{ 
    s.add(it.getProperty('fullcode')); 
};
fullcode.setProperty('fullcode', fullcode.getProperty('code') + " " + s.join(", "));

fullcode.out('USE').has('token', 'T_NS_SEPARATOR').each{
    s = [];
    it.out("SUBNAME").sort{it.order}._().each{ 
        s.add(it.getProperty('code')); 
    };
    it.setProperty('originpath', s.join('\\\\'));
    
    it.setProperty('originlastpath', s.pop());
}

fullcode.out('USE').has('token', 'T_AS').each{
    s = [];
    it.out("SUBNAME").sort{it.order}._().each{ 
        s.add(it.getProperty('code')); 
    };
    it.setProperty('originpath', s.join('\\\\'));
    
    it.setProperty('originlastpath', s.pop());
}

fullcode.out('USE').has('token', 'T_STRING').each{
    it.setProperty('originpath', it.code);
    
    it.setProperty('originlastpath', it.code);
}

GREMLIN;
    }
}
?>