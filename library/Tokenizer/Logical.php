<?php

namespace Tokenizer;

class Logical extends TokenAuto {
    function _check() {
        $this->conditions = array( -1 => array('atom' => 'yes'), 
                                   0 => array('token' => array('T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
                                                               'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
                                                               'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR'),
                                              'atom' => 'none' ),
                                   1 => array('atom' => 'yes'),
        );
        
        $this->actions = array('transform' => array( '-1' => 'LEFT',
                                                     '1' => 'RIGHT'),
                               'atom'     => 'Logical');
        return $this->checkAuto();
    }
}
?>