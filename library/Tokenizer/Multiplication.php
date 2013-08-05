<?php

namespace Tokenizer;

class Multiplication extends TokenAuto {
    static public $operators = array('T_STAR', 'T_SLASH', 'T_PERCENTAGE');
    static public $operands = array('Integer', 'Addition', 'Variable', 'Multiplication', 'Sign', 'Not',
                                    'Parenthesis', 'Property', 'Array', 'Concatenation', 'Float',
                                    'String', 'Preplusplus', 'Postplusplus', 'Nsname', 'Functioncall',
                                    'Methodcall', 'Staticmethodcall', 'Concatenation', 'Cast',
                                    'Noscream', 'Staticconstant', 'Staticproperty', 'Constant', 
                                    'Boolean',  );
    
    function _check() {

        $this->conditions = array(-2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_DOT')),
                                  -1 => array('atom' => Multiplication::$operands ),
                                   0 => array('token' => Multiplication::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => Multiplication::$operands),
                                   2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR',)),
        );
        
        $this->actions = array('makeEdge'   => array(  1 => 'RIGHT',
                                                      -1 => 'LEFT'
                                                      ),
                               'atom'       => 'Multiplication',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>