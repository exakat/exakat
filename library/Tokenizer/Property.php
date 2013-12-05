<?php

namespace Tokenizer;

class Property extends TokenAuto {
    static public $operators = array('T_OBJECT_OPERATOR');
    
    function _check() {
        $operands = array('Variable', 'Property', 'Array', 'Staticmethodcall', 'Staticproperty', 'Methodcall', 'Functioncall');
        $this->conditions = array( -2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                   -1 => array('atom' => $operands), 
                                    0 => array('token' => Property::$operators),
                                    1 => array('atom' => array('String', 'Variable', 'Array', 'Identifier')),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS')), //'T_OPEN_BRACKET'
                                    );
        
        $this->actions = array('makeEdge'   => array( -1 => 'OBJECT',
                                                       1 => 'PROPERTY'),
                               'atom'       => 'Property',
                               'cleanIndex' => true);
        $this->checkAuto(); 

        $this->conditions = array( -1 => array('atom'  => $operands), 
                                    0 => array('token' => Property::$operators),
                                    1 => array('token' => 'T_OPEN_CURLY'),
                                    2 => array('atom'  => 'yes'),
                                    3 => array('token' => 'T_CLOSE_CURLY'),
                                    4 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
                                    );
        
        $this->actions = array('transform'   => array( -1 => 'OBJECT',
                                                        1 => 'DROP',
                                                        2 => 'PROPERTY',
                                                        3 => 'DROP'),
                               'atom'       => 'Property',
                               'cleanIndex' => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>