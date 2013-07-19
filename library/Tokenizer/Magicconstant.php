<?php

namespace Tokenizer;

class Magicconstant extends TokenAuto {
    function _check() {

        $this->conditions = array( 0 => array('token' => array('T_CLASS_C','T_FUNC_C', 'T_DIR', 'T_FILE', 'T_LINE','T_METHOD_C', 'T_NS_C',),
                                               'atom' => 'none'),
                                 );
        
        $this->actions = array('atom'       => 'Magicconstant');
        
        return $this->checkAuto();
    }
}

?>