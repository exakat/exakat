<?php

namespace Tokenizer;

class Sign extends TokenAuto {
    function _check() {

        $operands = array('Integer', 'Sign', 'String', 'Variable', '_Array', 'Float', 'Boolean', 'Functioncall');
        $this->conditions = array( -1 => array('begin' => true), 
                                   0  => array('token' => array('T_PLUS', 'T_MINUS'),
                                               'atom' => 'none'),
                                   1  => array('atom' => $operands),
                                   2  => array('filterOut' => array('T_OPEN_PARENTHESIS')),
                                 );
        
        $this->actions = array('makeEdge'    => array( '1' => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true,)
                               );
        $r = $this->checkAuto();

        $this->conditions = array( -1 => array('token' => array('T_PLUS', 'T_MINUS', 'T_STAR', 'T_SLASH', 'T_PERCENTAGE')), 
                                    0 => array('token' => array('T_PLUS', 'T_MINUS'),
                                               'atom' => 'none'),
                                    1 => array('atom' => $operands),
                                 );
        
        $this->actions = array('makeEdge'   => array( '1' => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true,)
                               );
        
        return $r && $this->checkAuto();
    }
}

?>