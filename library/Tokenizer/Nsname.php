<?php

namespace Tokenizer;

class Nsname extends TokenAuto {
    function _check() {
        // @note a\b\c
        $this->conditions = array(-2 => array('filterOut' => 'T_NS_SEPARATOR'), 
                                  -1 => array('atom' => array('String', 'Nsname') ),
                                   0 => array('token' => 'T_NS_SEPARATOR'),
                                   1 => array('atom' => 'String'),
        );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ELEMENT',
                                                      -1 => 'ELEMENT'
                                                      ),
                               'order'    => array('1'  => '2',
                                                   '-1' => '1'
                                                      ),
                               'mergeNext'  => array('Nsname' => 'ELEMENT'), 
                               'atom'       => 'Nsname',
                               );
        $r = $this->checkAuto();

        // @note \a\b\c (\ initial)
        $this->conditions = array( -1 => array('filterOut2' => array('T_NS_SEPARATOR', 'T_STRING')),
                                   0 => array('token' => 'T_NS_SEPARATOR'),
                                   1 => array('atom' => 'String'),
        );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ELEMENT'),
                               'order'    => array('1'  => '1'),
                               'atom'       => 'Nsname',);
        $r = $this->checkAuto();

        return $r;
    }
}
?>