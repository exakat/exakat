<?php

namespace Tokenizer;

class Assignation extends TokenAuto {
    static public $operators =array( 'T_AND_EQUAL',
                                     'T_CONCAT_EQUAL',
                                     'T_EQUAL',
                                     'T_DIV_EQUAL',
                                     'T_MINUS_EQUAL',
                                     'T_MOD_EQUAL',
                                     'T_MUL_EQUAL',
                                     'T_OR_EQUAL',
                                     'T_PLUS_EQUAL',
                                     'T_SL_EQUAL',
                                     'T_SR_EQUAL',
                                     'T_XOR_EQUAL',
                                     'T_SL_EQUAL',
                                     'T_SR_EQUAL',
                                     );
    static public $atom = 'Assignation';
    
    public function _check() {
        $operands = array('Integer', 'Multiplication', 'Addition', 'Not',
                          'Array', 'Float', 'Concatenation', 'Property',
                          'Parenthesis', 'Noscream', 'Ternary', 'New', 'String',
                          'Constant', 'Functioncall', 'Staticproperty', 'Staticconstant', 'Property',
                          'Heredoc', 'Preplusplus', 'Postplusplus', 'Methodcall', 'Nsname', 
                          'Assignation', 'Variable', 'Boolean', 'Magicconstant',
                          'Cast', 'Staticmethodcall', 'Sign', 'Logical', 'Bitshift', 'Comparison', 
                          'Clone', 'Shell', 'Include', 'Instanceof', 'Function', 'ArrayNS', 'Identifier',
                          'Arrayappend',
                         );
        
        // check for preplusplus in the yield filterout.
        $this->conditions = array(-2 => array('filterOut2' => array_merge(array('T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_AND', 'T_DOLLAR'),
                                                                           Preplusplus::$operators )),
                                  -1 => array('atom' => array('Variable', 'Array', 'Property', 'Staticproperty', 'Functioncall', 
                                                              'Noscream', 'Not', 'Arrayappend' , 'Typehint', 'Identifier',
                                                              'Static', 'Cast', 'Sign' )),
                                   0 => array('token' => Assignation::$operators),
                                   1 => array('atom' => $operands),
                                   2 => array('filterOut2' => array_merge(Assignation::$operators, Addition::$operators, Bitshift::$operators, 
                                                                          Comparison::$operators, Logical::$operators, Multiplication::$operators, 
                                                                          PostPlusplus::$operators, 
                                                                          array('T_DOT', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 
                                                                                'T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET', 
                                                                                'T_QUESTION', 'T_NS_SEPARATOR' ))),
        );
        
        $this->actions = array('makeEdge'    => array( 1 => 'RIGHT',
                                                      -1 => 'LEFT'),
                               'atom'       => 'Assignation',
                               'cleanIndex' => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        return $this->checkRemaining();
    } 

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("LEFT").next().getProperty('fullcode') + " " + fullcode.getProperty('code') + " " + fullcode.out("RIGHT").next().getProperty('fullcode')); 

GREMLIN;
    }
}
?>