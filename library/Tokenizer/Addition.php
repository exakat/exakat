<?php

namespace Tokenizer;

class Addition extends TokenAuto {
    function _check() {
        $operands = array('Integer', 'Addition', 'Variable', 'Multiplication','Sign','Not', 'Parenthesis', 'Object', 'Array', 'Concatenation', 'Float',
                          'String', );
        
        $this->conditions = array(-1 => array('atom' => $operands ),
                                   0 => array('code' => array('+','-'),
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