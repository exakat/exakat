<?php

namespace Tokenizer;

class Multiplication extends TokenAuto {
    static public $operators = array('T_STAR', 'T_SLASH', 'T_PERCENTAGE');
    static public $operands = array('Integer', 'Addition', 'Variable', 'Multiplication', 'Sign', 'Not',
                                    'Parenthesis', 'Property', 'Array', 'Concatenation', 'Float',
                                    'String', 'Identifier', 'Preplusplus', 'Postplusplus', 'Nsname', 'Functioncall',
                                    'Methodcall', 'Staticmethodcall', 'Concatenation', 'Cast',
                                    'Noscream', 'Staticconstant', 'Staticproperty', 'Constant', 
                                    'Boolean', 'Magicconstant', 'Assignation', 'Include', 'Power',
                                    'Staticclass', 'Null' );
    static public $atom = 'Multiplication';
    
    public function _check() {

        $this->conditions = array(-2 => array('filterOut' => array_merge(Property::$operators, 
                                                                         Staticproperty::$operators,
                                                                         Concatenation::$operators, 
                                                                         Preplusplus::$operators,
                                                                         Power::$operators)),
                                  -1 => array('atom' => Multiplication::$operands ),
                                   0 => array('token' => Multiplication::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => Multiplication::$operands),
                                   2 => array('filterOut' => array_merge(array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET', 
                                                                               'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR'),
                                                                          Assignation::$operators,
                                                                          Power::$operators)),
        );
        
        $this->actions = array('makeEdge'     => array(  1 => 'RIGHT',
                                                        -1 => 'LEFT'),
                               'atom'         => 'Multiplication',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode',  fullcode.out("LEFT").next().getProperty('fullcode') + " " + fullcode.getProperty('code') 
                                    + " " + fullcode.out("RIGHT").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>