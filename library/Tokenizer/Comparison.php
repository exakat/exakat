<?php

namespace Tokenizer;

class Comparison extends TokenAuto {
    static public $operators = array('T_IS_EQUAL','T_IS_NOT_EQUAL', 'T_IS_GREATER_OR_EQUAL', 'T_IS_SMALLER_OR_EQUAL', 'T_IS_IDENTICAL', 'T_IS_NOT_IDENTICAL', 'T_GREATER', 'T_SMALLER');
    static public $atom = 'Comparison';

    public function _check() {
        $operands = array('Variable', 'Array', 'Property', 'Integer', 'Sign', 'Float', 'Constant', 'Boolean', 'Null',
                          'Property', 'Staticproperty', 'Methodcall', 'Staticmethodcall', 'Functioncall',
                          'Magicconstant', 'Staticconstant', 'String', 'Addition', 'Multiplication',
                          'Nsname', 'Not', 'Parenthesis', 'Noscream', 'Preplusplus', 'Postplusplus',
                          'Bitshift', 'Concatenation', 'Cast', 'New', 'Include' , 'Identifier', 'Instanceof',
                          'Staticclass', 'Comparison', 'Shell');
        
        $this->conditions = array(-2 => array('filterOut' => array_merge(array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON'),
                                                                         Addition::$operators, Bitshift::$operators,
                                                                         Multiplication::$operators, Preplusplus::$operators,
                                                                         Concatenation::$operators, _New::$operators,
                                                                         Comparison::$operators)),
                                  -1 => array('atom' => $operands ),
                                   0 => array('token' => Comparison::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => array_merge($operands, array('Assignation'))),
                                   2 => array('filterOut' => array_merge(array('T_OPEN_PARENTHESIS', 'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON'),
                                                           Addition::$operators, Multiplication::$operators, Assignation::$operators, Concatenation::$operators,
                                                           Postplusplus::$operators )),
        );
        
        $this->actions = array('transform'    => array( 1 => 'RIGHT',
                                                       -1 => 'LEFT'),
                               'atom'         => 'Comparison',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

it.setProperty('fullcode', it.out("LEFT").next().getProperty('fullcode') + " " + it.getProperty('code') + " " + it.out("RIGHT").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
