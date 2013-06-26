<?php

namespace Tokenizer;

class Preplusplus extends TokenAuto {
    function _check() {
        $this->conditions = array( 0 => array('token' => array('T_INC', 'T_DEC')),
                                   1 => array('atom' => array('Variable', 'Array', 'Property', 'Functioncall', 'Staticproperty' )),
                                   2 => array('filterOut' => array('T_DOUBLECOLON', 'T_OBJECT_OPERATOR' )),
        );
        
        $this->actions = array('transform'    => array( 1 => 'PREPLUSPLUS'),
                               'atom'       => 'Preplusplus');
                               
        return $this->checkAuto();
    }
}

?>