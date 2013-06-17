<?php

namespace Tokenizer;

class Functioncall extends TokenAuto {
    function _check() {
        
        $this->conditions = array( 0 => array('token' => array('T_STRING', 'T_ECHO'),
                                              'atom' => 'none'),
                                   1 => array('atom' => 'none',
                                              'code' => '(' ),
                                   2 => array('atom' => 'Arguments'),
                                   3 => array('atom' => 'none',
                                              'code' => ')' ),
        );
        
        $this->actions = array('makeEdge'    => array('2' => 'ARGUMENTS',),
                               'dropNext'   => array(1),
                               'atom'       => 'Functioncall',
                               );
        $r = $this->checkAuto();

        $this->conditions = array( 0 => array('token' => array('T_ECHO'),
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Arguments'),
        );
        
        $this->actions = array('makeEdge'    => array('1' => 'ARGUMENTS',),
                               'atom'       => 'Functioncall',
                               );
        $r = $this->checkAuto();
        
        return $r;
    }
}
?>