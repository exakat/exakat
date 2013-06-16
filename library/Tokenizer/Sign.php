<?php

namespace Tokenizer;

class Sign extends TokenAuto {
    function _check() {

        $operands = array('Integer', 'Sign', 'String', 'Variable', '_Array', 'Float', 'Boolean');
        $this->conditions = array( -1 => array('begin' => true), 
                                   0  => array('token' => array('+', '-'),
                                               'atom' => 'none'),
                                   1  => array('atom' => $operands),
                                 );
        
        $this->actions = array('makeEdge'    => array( '1' => 'SIGN'),
                               'atom'       => 'Sign',
                               );
        $r = $this->checkAuto();

        $this->conditions = array( -1 => array('token' => array('+', '-', '*', '/', '%' )), 
                                   0  => array('token' => array('+', '-'),
                                               'atom' => 'none'),
                                   1  => array('atom' => $operands),
                                 );
        
        $this->actions = array('makeEdge'    => array( '1' => 'SIGN'),
                               'atom'       => 'Sign',
                               );
        
        return $r && $this->checkAuto();
    }
}

?>