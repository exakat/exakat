<?php

namespace Tokenizer;

class Sign extends TokenAuto {
    function _check() {

        $this->conditions = array( -1 => array('begin' => true), 
                                   0 => array('token' => array('+', '-'),
                                               'atom' => 'none'),
                                   1 => array('atom' => array('Integer', 'Sign')),
                                 );
        
        $this->actions = array('makeEdge'    => array( '1' => 'SIGN'),
//                               'changeNext' => array(1),
                               'atom'       => 'Sign',
                               );
        $r = $this->checkAuto();

        $this->conditions = array( -1 => array('token' => array('+', '-')), 
                                   0 => array('token' => array('+', '-'),
                                               'atom' => 'none'),
                                   1 => array('atom' => array('Integer', 'Sign')),
                                 );
        
        $this->actions = array('addEdge'    => array( '1' => 'SIGN'),
                               'changeNext' => array(1),
                               'atom'       => 'Sign',
                               );
        return $r && $this->checkAuto();
    }
}

?>