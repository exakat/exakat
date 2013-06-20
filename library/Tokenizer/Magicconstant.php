<?php

namespace Tokenizer;

class Magicconstant extends TokenAuto {
    function _check() {

        $this->conditions = array( 0 => array('token' => array('T_CLASS_C','T_FUNC_C', 'T_DIR', 'T_FILE', 'T_LINE','T_METHOD_C'),
                                               'atom' => 'none'),
                                   1 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
                                 );
        
        $this->actions = array('atom'       => 'Magicconstant',
                               );
        
        return $this->checkAuto();
    }
}

?>