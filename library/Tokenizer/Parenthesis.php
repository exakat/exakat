<?php

namespace Tokenizer;

class Parenthesis extends TokenAuto {
    function _check() {
        $this->conditions = array(-1 => array('filterOut' => array('T_STRING', 'T_ECHO')), 
                                   0 => array('code' => '(' ),
                                   1 => array('atom' => 'yes'),
                                   2 => array('code' => ')'),
        );
        
        $this->actions = array('makeEdge' => array( '1' => 'CODE'),
                               'dropNext' => array(1),
                               'atom'     => 'Parenthesis');
        return $this->checkAuto();
    }
}
?>