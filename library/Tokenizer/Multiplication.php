<?php

namespace Tokenizer;

class Multiplication extends TokenAuto {
    function _check() {

        $operands = array('Integer', 'Multiplication', 'Variable', 'Not', 'Sign', 'Parenthesis', 'Object');
        
        $this->conditions = array(-1 => array('atom' => $operands ),
                                  0 => array('code' => array('*','/','%'),
                                             'atom' => 'none'),
                                  1 => array('atom' => $operands),
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'
                                                      ),
                               'atom'       => 'Multiplication',
                               'cleansemicolon' => 1);
    
        return $this->checkAuto();
    }
    
    function reserve() {
        Token::$reserved[] = '*';
        Token::$reserved[] = '/';
        Token::$reserved[] = '%';
        
        return true;
    }

}

?>