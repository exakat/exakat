<?php

namespace Tokenizer;

class Comparison extends TokenAuto {
    function _check() {
    
        $operands = array('Variable', 'Array', 'Object', 'Integer', 'Sign', 'Float', );
        $operators = array('==','!=', '>=', '<=', '===', '!==' );
        
        $this->conditions = array(-1 => array('atom' => $operands ),
                                   0 => array('code' => $operators,
                                             'atom' => 'none'),
                                   1 => array('atom' => $operands),
        );
        
        $this->actions = array('makeEdge'    => array('1' => 'RIGHT',
                                                      '-1' => 'LEFT'
                                                      ),
                               'atom'       => 'Comparison',
                               );
                               
        $r = $this->checkAuto(); 

        return $r;
    }

    
    function reserve() {
        Token::$reserved[] = '==';
        Token::$reserved[] = '!=';
        Token::$reserved[] = '>=';
        Token::$reserved[] = '<=';
        Token::$reserved[] = '===';
        Token::$reserved[] = '!==';

        return true;
    }
}

?>