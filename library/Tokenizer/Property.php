<?php

namespace Tokenizer;

class Property extends TokenAuto {
    function _check() {
        
        $operands = array('Variable', 'Property', '_Array');
        $this->conditions = array( -1 => array('atom' => $operands), 
                                    0 => array('token' => 'T_OBJECT_OPERATOR'),
                                    1 => array('atom' => 'String'),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
                                    
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'OBJECT',
                                                       1 => 'PROPERTY'),
                               'atom'       => 'Property',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }

    function reserve() {
        Token::$reserved[] = 'T_OBJECT_OPERATOR';
    }
}

?>