<?php

namespace Tokenizer;

class Postplusplus extends TokenAuto {
    function _check() {
        $this->conditions = array(-1 => array('atom' => array('Variable', 'Array', 'Property', 'Functioncall', 'Staticproperty' )),
                                   0 => array('token' => array('T_INC', 'T_DEC')),
                                   1 => array('filterOut' => array('T_DOUBLECOLON')),
        );
        
        $this->actions = array('transform'    => array( '-1' => 'POSTPLUSPLUS'),
                               'atom'       => 'Postplusplus');
                               
        return $this->checkAuto();
    }
}

?>