<?php

namespace Tokenizer;

class Constant extends TokenAuto {
    function _check() {

        $this->conditions = array( 0 => array('token' => array('T_CONSTANT_ENCAPSED_STRING', 'T_STRING'),
                                               'atom' => 'none'),
                                   1 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
                                 );
        
        $this->actions = array('atom'       => 'Constant',
                               );
        
        return $this->checkAuto();
    }
}

?>