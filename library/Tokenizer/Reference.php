<?php

namespace Tokenizer;

class Reference extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_AND',
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Variable', 'Array', 'Property')),
                                  2 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'REFERENCE'),
                               'atom'       => 'Reference');
                               
        return $this->checkAuto();
    }
}

?>