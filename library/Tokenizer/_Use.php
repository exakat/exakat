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
        
        $this->actions = array('transform'    => array( 1 => 'USE'),
                               'atom'         => 'Use',
                               'cleanIndex'   => true,
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

    // use const \a\b;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('token' => array('T_CONST', 'T_FUNCTION')),
                                   2 => array('atom'  => array('Nsname', 'Identifier', 'As')),
                                   3 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'  => 'none'),
                                 );
        
        $this->actions = array('to_use_const'       => true,
                               'atom'         => 'Use',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto(); 

    // use const \b\c, \a\c;
        $this->conditions = array( 0 => array('token' => _Use::$operators),
                                   1 => array('token' => array('T_CONST', 'T_FUNCTION')),
                                   2 => array('atom'  => 'Arguments'),
                                   3 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG'),
                                              'atom'  => 'none'),
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
//        $this->checkAuto(); 

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
    a = it.getProperty('fullcode');
    s.add(a); 
};
fullcode.setProperty('fullcode', fullcode.getProperty('code') + " " + s.join(", "));

// use a (aka c);
fullcode.out('USE').has('atom', 'Identifier').each{
    it.setProperty('originpath', it.code);
    
    it.setProperty('alias', it.code);
}

// use a\b\c (aka c);
fullcode.out('USE').has('atom', 'As').each{
    s = [];
    it.out("SUBNAME").sort{it.order}._().each{ 
        s.add(it.getProperty('code')); 
    };
    if (it.absolutens == 'true') {
        it.setProperty('originpath', '\\\\' + s.join('\\\\'));
    } else {
        it.setProperty('originpath', s.join('\\\\'));
    }
    
    it.setProperty('alias', it.out('AS').next().code);
}

// use a; (aka a)
fullcode.out('USE').has('atom', 'Nsname').each{
    s = [];
    it.out("SUBNAME").sort{it.order}._().each{ 
        s.add(it.getProperty('code')); 
    };
    if (it.absolutens == 'true') {
        it.setProperty('originpath', '\\\\' + s.join('\\\\'));
    } else {
        it.setProperty('originpath', s.join('\\\\'));
    }
    
    if (it.out('AS').any()) {
        it.setProperty('alias', it.out('AS').next().code);
    } else {
        it.setProperty('alias', s.pop());
    }
}

GREMLIN;
    }
}
?>