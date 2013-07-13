<?php

namespace Tokenizer;

class _For extends TokenAuto {
    function _check() {
    
    // @doc for(a; b; c) { code }
        $this->conditions = array( 0 => array('token' => 'T_FOR',
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom' => 'yes'),
                                   3 => array('token' => 'T_SEMICOLON'),
                                   4 => array('atom' => 'yes'),
                                   5 => array('token' => 'T_SEMICOLON'),
                                   6 => array('atom' => 'yes'),
                                   7 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   8 => array('atom' => 'Block'),
        );
        
        $this->actions = array('transform'    => array('1' => 'DROP',
                                                       '2' => 'INIT',    
                                                       '3' => 'DROP',
                                                       '4' => 'FINAL',
                                                       '5' => 'DROP',
                                                       '6' => 'INCREMENT',
                                                       '7' => 'DROP',
                                                       '8' => 'CODE',
                                                      ),
                               'atom'       => 'For',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }
}

?>