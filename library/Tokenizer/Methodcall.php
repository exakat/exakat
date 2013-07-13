<?php

namespace Tokenizer;

class Methodcall extends TokenAuto {
    function _check() {
        
        $operands = array('Variable', 'Property', 'Array', 'Functioncall', 'Methodcall', 'Staticmethodcall', 'Staticproperty' );
        $this->conditions = array( -2 => array('filterOut' => array('T_DOUBLE_COLON')),
                                   -1 => array('atom' => $operands), 
                                    0 => array('token' => 'T_OBJECT_OPERATOR'),
                                    1 => array('atom' => 'Functioncall')
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'OBJECT',
                                                       1 => 'METHOD'),
                               'atom'       => 'Methodcall',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }
}

?>