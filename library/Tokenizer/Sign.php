<?php

namespace Tokenizer;

class Sign extends TokenAuto {
    static public $operators = array('T_PLUS', 'T_MINUS');
    static public $operands = array('Integer', 'Sign', 'String', 'Variable', 'Array', 'Float', 'Boolean', 'Functioncall',
                                    'Staticmethodcall', 'Staticproperty', 'Multiplication', 'Property', 'Parenthesis', 'Methodcall', 'Cast');

    function _check() {
        //  + -1 
        $this->conditions = array( -1 => array('filterOut2' => array_merge(array('T_STRING', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 
                                                                                 'T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER', 
                                                                                 'T_CLOSE_PARENTHESIS', 'T_VARIABLE', 'T_DOT', 
                                                                                 'T_CLOSE_BRACKET', 'T_BANG',),
                                                                          Magicconstant::$operators),
                                               'notAtom' => array('Sign', 'Addition', 'Array', 'Parenthesis', 'Noscream', 'Multiplication', 'Cast' )), 
                                    0 => array('token' => Sign::$operators,
                                               'atom' => 'none'
                                               ),
                                    1 => array('atom' => 'Integer'),
                                    2 => array('filterOut' => array_merge(array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
                                                                                'T_OPEN_CURLY', 'T_OPEN_BRACKET'),
                                                                    Multiplication::$operators)),
                                 );
        
        $this->actions = array('atom'       => 'Integer',
                               'sign'       => true,
                               'property'   => array('scalar' => true,
                                                     'instruction' => true,
                                                     'signed' => true),
                               'cleanIndex' => true
                               );
        $this->checkAuto();
        
        //  + -1 
        $this->conditions = array( -1 => array('filterOut2' => array_merge(array('T_STRING', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 
                                                                                 'T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER', 
                                                                                 'T_CLOSE_PARENTHESIS', 'T_VARIABLE', 'T_DOT', 
                                                                                 'T_CLOSE_BRACKET', 'T_BANG',),
                                                                          Magicconstant::$operators),
                                               'notAtom' => array('Sign', 'Addition', 'Array', 'Parenthesis', 'Noscream', 'Multiplication', 'Cast' )), 
                                    0 => array('token' => Sign::$operators,
                                               'atom' => 'none'
                                               ),
                                    1 => array('atom' => Sign::$operands),
                                    2 => array('filterOut' => array_merge(array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
                                                                                'T_OPEN_CURLY', 'T_OPEN_BRACKET'),
                                                                    Multiplication::$operators)),
                                 );
        
        $this->actions = array('makeEdge'   => array( 1 => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true),
                               'cleanIndex' => true
                               );
        $this->checkAuto();

// This special case is needed for situation like 1 . 2 + 3 and -'a' . -'b';
        $this->conditions = array( -1 => array('token' => array('T_DOT' ),
                                               'atom' => 'none'), 
                                   0  => array('token' => Sign::$operators,
                                               'atom' => 'none'),
                                   1  => array('atom' => Sign::$operands),
                                   2  => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
                                                                    'T_OPEN_CURLY', 'T_OPEN_BRACKET')),
                                 );
        
        $this->actions = array('makeEdge'   => array( 1 => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true),
                               'cleanIndex' => true);
        $this->checkAuto();

//Special cases like 1 * -2 or 2 + -2         
        $this->conditions = array( -1 => array('token' => array_merge(Addition::$operators, Multiplication::$operators), 'atom' => 'none'), 
                                    0 => array('token' => Sign::$operators,
                                               'atom' => 'none'),
                                    1 => array('atom' => Sign::$operands),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
                                                                    'T_OPEN_CURLY', 'T_OPEN_BRACKET')));
        
        $this->actions = array('makeEdge'   => array( 1 => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true),
                               'cleanIndex' => true);
        $this->checkAuto();
                
        return $this->checkRemaining();
    }

    function fullcode() {
        return 'it.fullcode = it.code + it.out("SIGN").next().fullcode; ';
    }
}

?>