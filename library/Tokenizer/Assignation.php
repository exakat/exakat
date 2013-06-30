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
                                     );
    
    function _check() {
        $operands = array('Integer', 'Multiplication', 'Addition', 'Not',
                          'Array', 'Float', 'Concatenation', 'Property',
                          'Parenthesis', 'Noscream', 'Ternary', 'New', 'String',
                          'Constant', 'Functioncall', 'Staticproperty', 'Staticconstant', 'Property',
                          'Heredoc', 'Preplusplus', 'Postplusplus', 'Methodcall', 'Nsname', 
                          'Assignation', 'Variable', 'Reference', 'Boolean', 'Magicconstant',
                         );
        
        $this->conditions = array(-1 => array('atom' => array('Variable', 'Array', 'Property', 'Staticproperty', 'Functioncall','Noscream', 'Reference', 'Not', 'Arrayappend' )),
                                   0 => array('token' => Assignation::$operators),
                                   1 => array('atom' => $operands),
                                   2 => array('filterOut' => array_merge(Assignation::$operators,
                                                                         array('T_DOT', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 
                                                                               'T_OPEN_PARENTHESIS',))),
                                  
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'),
                               'atom'       => 'Assignation');

        return $this->checkAuto();
    } 
    
    function reserve() {
        Token::$reserved = array_merge(Token::$reserved, Assignation::$operators);

        return true;
    }
    
}
?>