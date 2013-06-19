<?php

namespace Tokenizer;

class _New extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_NEW',
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Functioncall', 'Constant', 'Variable', 'Methodcall')),
                                  2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR')),
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'NEW'),
                               'atom'       => 'New');

        return $this->checkAuto();
    }
}

?>