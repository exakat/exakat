<?php

namespace Tokenizer;

class Sign extends TokenAuto {
    static public $operators = array('T_PLUS', 'T_MINUS');
    static public $operands = array('Sign', 'String', 'Variable', 'Array', 'Float', 'Boolean', 'Functioncall', 'Null',
                                    'Staticmethodcall', 'Staticproperty', 'Multiplication', 'Property', 'Parenthesis', 
                                    'Methodcall', 'Cast', 'Constant', 'Boolean', 'Identifier', 'Assignation', 'Staticconstant');
    static public $atom = 'Sign';

    public function _check() {
        //  + -1  (special case for Integers)
        $this->conditions = array( -1 => array('filterOut2' => array_merge(array('T_STRING', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 
                                                                                 'T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER', 
                                                                                 'T_CLOSE_PARENTHESIS', 'T_VARIABLE', 'T_DOT', 
                                                                                 'T_CLOSE_BRACKET', 'T_BANG', 'T_CLOSE_CURLY', 
                                                                                 'T_QUOTE_CLOSE', 'T_QUOTE', 'T_SHELL_QUOTE_CLOSE', 'T_SHELL_QUOTE' ),
                                                                          Magicconstant::$operators, Preplusplus::$operators),
                                               'notAtom' => array('Sign', 'Addition', 'Array', 'Parenthesis', 'Noscream', 'Multiplication', 'Cast' )), 
                                    0 => array('token' => Sign::$operators,
                                               'atom' => 'none'),
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
        
        //  + -$s (Normal case)
        $this->conditions = array( -1 => array('filterOut2' => array_merge(array('T_STRING', 'T_ARRAY', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 
                                                                                 'T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER', 
                                                                                 'T_CLOSE_PARENTHESIS', 'T_VARIABLE', 'T_DOT', 
                                                                                 'T_CLOSE_BRACKET', 'T_CLOSE_CURLY' ),
                                                                          Magicconstant::$operators, Not::$operators),
                                               'notAtom' => array('Sign', 'Addition', 'Array', 'Parenthesis', 'Noscream', 'Multiplication', 'Cast',  'Integer', 'Real' )), 
                                    0 => array('token' => Sign::$operators),
                                    1 => array('atom' => Sign::$operands),
                                    2 => array('filterOut' => array_merge( Methodcall::$operators, Parenthesis::$operators, 
                                                                           _Array::$operators,     Block::$operators,
                                                                           Property::$operators,   Staticproperty::$operators)),
                                 );
        
        $this->actions = array('transform'    => array( 1 => 'SIGN'),
                               'atom'         => 'Sign',
                               'property'     => array('scalar' => true,
                                                       'instruction' => true),
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
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
        
        $this->actions = array('transform'  => array( 1 => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true),
                               'cleanIndex' => true);
        $this->checkAuto();

//Special cases like 1 * -2 or 2 + -2         
        $this->conditions = array( -1 => array('token' => array_merge(Addition::$operators, Multiplication::$operators), 
                                               'atom'  => 'none'), 
                                    0 => array('token' => Sign::$operators,
                                               'atom'  => 'none'),
                                    1 => array('atom'  => Sign::$operands),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
                                                                    'T_OPEN_CURLY', 'T_OPEN_BRACKET')));
        
        $this->actions = array('transform'  => array( 1 => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true),
                               'cleanIndex' => true);
        $this->checkAuto();
                
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
if (fullcode.out('SIGN').count() > 0) {
    fullcode.fullcode = fullcode.code + fullcode.out("SIGN").next().fullcode; 
} else {
    fullcode.fullcode = fullcode.code; 
}
GREMLIN;
    }
}

?>