<?php

namespace Tokenizer;

class Staticproperty extends TokenAuto {
    function _check() {
        
        $operands = array('Constant');
        $this->conditions = array( -1 => array('atom' => $operands), 
                                    0 => array('token' => 'T_DOUBLE_COLON'),
                                    1 => array('atom' => array('Variable', 'Array')),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
                                    
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'CLASS',
                                                       1 => 'PROPERTY'),
                               'atom'       => 'Staticproperty',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }

    function reserve() {
        Token::$reserved[] = 'T_DOUBLE_COLON';
    }
}

?>