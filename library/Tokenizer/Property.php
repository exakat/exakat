<?php

namespace Tokenizer;

class Property extends TokenAuto {
    static public $operators = array('T_OBJECT_OPERATOR');
    
    function _check() {
        $operands = array('Variable', 'Property', 'Array', 'Staticmethodcall', 'Methodcall', );
        $this->conditions = array( -1 => array('atom' => $operands), 
                                    0 => array('token' => Property::$operators),
                                    1 => array('atom' => array('String', 'Variable', 'Block')),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
                                    );
        
        $this->actions = array('makeEdge'   => array( -1 => 'OBJECT',
                                                       1 => 'PROPERTY'),
                               'atom'       => 'Property',
                               'cleanIndex' => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>