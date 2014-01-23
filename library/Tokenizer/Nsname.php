<?php

namespace Tokenizer;

class Nsname extends TokenAuto {
    static public $operators = array('T_NS_SEPARATOR');

    function _check() {
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

        return $this->checkRemaining();
    }

    function fullcode() {
        return <<<GREMLIN
s = []; 
it.out("ELEMENT").sort{it.order}._().each{ s.add(it.fullcode); };
it.setProperty('fullcode', s.join("\\\\"));
GREMLIN;
    }
}
?>