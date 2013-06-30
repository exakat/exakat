<?php

namespace Tokenizer;

class Addition extends TokenAuto {
    static public $operators = array('T_PLUS','T_MINUS');
    
    function _check() {
        $operands = array('Integer', 'Addition', 'Variable', 'Multiplication','Sign','Not', 'Parenthesis', 
                          'Property', 'Array', 'Concatenation', 'Float', 'String', 'Preplusplus', 'Postplusplus',
                          'Nsname', );
        
        $this->conditions = array(-1 => array('atom' => $operands ),
                                   0 => array('token' => Addition::$operators,
                                             'atom' => 'none'),
                                   1 => array('atom' => $operands),
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