<?php

namespace Tokenizer;

class Nsname extends TokenAuto {
    static public $operators = array('T_NS_SEPARATOR');

    public function _check() {
        // @note \a\b\c (\ initial)
        $this->conditions = array( -1 => array('filterOut2' => array('T_NS_SEPARATOR', 'T_STRING')),
                                    0 => array('token' => Nsname::$operators),
                                    1 => array('atom' => 'Identifier'),
        );
        
        $this->actions = array('transform'   => array( 1 => 'ELEMENT'),
                               'order'       => array( '1'  => '0'),
                               'atom'        => 'Nsname',
                               'keepIndexed' => true,
                               'cleanIndex'  => true,
                               'property'    => array('absolutens' => 'true'),
                               );
        $this->checkAuto();

        // @note a\b\c
        $this->conditions = array(//-2 => array('filterOut' => 'T_NS_SEPARATOR'), 
                                  -1 => array('atom' => array('Identifier', 'Nsname') ),
                                   0 => array('token' => Nsname::$operators),
                                   1 => array('atom' => array('Identifier', 'Nsname')),
        );
        
        $this->actions = array('transform'   => array( 1 => 'ELEMENT',
                                                      -1 => 'ELEMENT'
                                                      ),
                               'order'       => array( '1'  => '1',
                                                      '-1' => '0'),
                               'mergeNext'   => array('Nsname' => 'ELEMENT'), 
                               'keepIndexed' => true,
                               'atom'        => 'Nsname',
                               'cleanIndex'  => true,
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
fullcode.out("ELEMENT").sort{it.order}._().each{ s.add(it.fullcode); };

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