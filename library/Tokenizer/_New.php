<?php

namespace Tokenizer;

class _New extends TokenAuto {
    static public $operators = array('T_NEW');

    function _check() {
        $this->conditions = array(0 => array('token' => _New::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Functioncall', 'Constant', 'Variable', 'Methodcall', 'String', 'Array', 'Property', 'Staticproperty', 'Staticmethodcall', 'Nsname', 'Identifier',)),
                                  2 => array('filterOut2' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_NS_SEPARATOR', 'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_DOUBLE_COLON' )),
        );
        
        $this->actions = array('makeEdge'   => array( 1 => 'NEW'),
                               'atom'       => 'New',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>