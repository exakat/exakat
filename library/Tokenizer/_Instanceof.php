<?php

namespace Tokenizer;

class _Instanceof extends TokenAuto {
    function _check() {

        $this->conditions = array(-1 => array('atom' => 'yes'),
                                  0 => array('token' => 'T_INSTANCEOF',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'),
                               'atom'       => 'Instanceof');

        return $this->checkAuto();
    } 
    
    function reserve() {
        Token::$reserved[] = 'T_INSTANCEOF';

        return true;
    }
    
}
?>