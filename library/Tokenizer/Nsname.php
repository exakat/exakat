<?php

namespace Tokenizer;

class Nsname extends TokenAuto {
    static public $operators = array('T_NS_SEPARATOR');
    static public $atom = 'Nsname';

    public function _check() {
        // @note \a\b\c (\ initial)
        $this->conditions = array( -2 => array('notToken' => 'T_NS_SEPARATOR'), 
                                   -1 => array('filterOut2' => 'T_NS_SEPARATOR'), // 'T_STRING', 'T_NAMESPACE'
                                    0 => array('token' => Nsname::$operators,
                                               'atom'  => 'none'),
                                    1 => array('atom' => 'Identifier'),
        );

        $this->actions = array('makeNamespace' => true,
                               'atom'          => 'Nsname',
                               'keepIndexed'   => true,
                               );
        $this->checkAuto();
        
        // @note a\b\c as F
        $this->conditions = array( 0 => array('token' => Nsname::$operators),
                                   1 => array('token' => 'T_AS'),
                                   2 => array('atom'  => 'Identifier'),
        );
        
        $this->actions = array('transform'   => array( 1 => 'DROP',
                                                       2 => 'AS' ),
                               'atom'        => 'Nsname',
                               'cleanIndex'  => true,
                               );
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

s = []; 
fullcode.out("SUBNAME").sort{it.order}._().each{ s.add(it.fullcode); };

if (fullcode.absolutens == 'true') {
    fullcode.setProperty('fullcode', "\\\\");
} else {
    fullcode.setProperty('fullcode', "");
}

if (s.size() == 0) { // no ELEMENT : simple NS
    fullcode.setProperty('fullcode', fullcode.getProperty('fullcode') + fullcode.getProperty('code'));
} else {
    fullcode.setProperty('fullcode', fullcode.getProperty('fullcode') + s.join("\\\\"));
}

GREMLIN;
    }
}
?>