<?php

namespace Tokenizer;

class Addition extends TokenAuto {
    static public $operators = array('T_PLUS','T_MINUS');
    
    function _check() {
        $operands = array('Integer', 'Addition', 'Variable', 'Multiplication','Sign','Not', 'Parenthesis', 
                          'Property', 'Array', 'Concatenation', 'Float', 'String', 'Preplusplus', 'Postplusplus',
                          'Nsname', );
        
        // note : Multiplication:: and Addition:: operators are the same! 
        $this->conditions = array(-1 => array('atom' => Multiplication::$operands ),
                                   0 => array('token' => Addition::$operators,
                                             'atom' => 'none'),
                                   1 => array('atom' => Multiplication::$operands),
        );
        
        $this->actions = array('makeEdge'    => array('1' => 'RIGHT',
                                                      '-1' => 'LEFT'
                                                      ),
                               'atom'       => 'Addition',
                               );
        return $this->checkAuto();
    }

    function reserve() {
        Token::$reserved[] = 'T_PLUS';
        Token::$reserved[] = 'T_MINUS';
        
        return true;
    }
}
?>