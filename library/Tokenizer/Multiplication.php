<?php

namespace Tokenizer;

class Multiplication extends TokenAuto {
    function check() {

        $operands = array('Integer', 'Multiplication', 'Variable');
        
        $this->conditions = array(-1 => array('atom' => $operands ),
                                  0 => array('code' => array('*','/','%'),
                                             'atom' => 'none'),
                                  1 => array('atom' => $operands),
        );
        
        $this->actions = array('addEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'),
                               'changeNext' => array(1, -1),
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