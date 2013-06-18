<?php

namespace Tokenizer;

class Noscream extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_AT',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR')),
                                  
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'AT'),
                               'atom'       => 'Noscream');

        return $this->checkAuto();
    }
}

?>