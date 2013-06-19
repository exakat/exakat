<?php

namespace Tokenizer;

class Ternary extends TokenAuto {
    function _check() {
        
        $operands = array('Constant');
        $this->conditions = array( -2 => array('filterOut' => array('T_BANG', 'T_AT')),
                                   -1 => array('atom' => 'yes'),
                                    0 => array('token' => 'T_QUESTION'),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => 'T_COLON'),
                                    3 => array('atom' => 'yes')
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'CONDITION',
                                                       1 => 'THEN',
                                                       3 => 'ELSE',
                                                       ),
                               'dropNextCode'   => array(":"), 
                               'atom'       => 'Ternary',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }
}

?>