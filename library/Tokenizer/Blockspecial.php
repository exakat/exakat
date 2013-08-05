<?php

namespace Tokenizer;

class Blockspecial extends TokenAuto {

    function _check() {
        $this->conditions = array( -1 => array('token' => 'T_ELSE',
                                               'atom' => 'none'),
                                    0 => array('notAtom' => 'Block', 'atom' => 'yes', ),
                                    1 => array('token' => 'T_SEMICOLON')
        );
        
        $this->actions = array( 'to_block' => true);
        $this->checkAuto(); 

        $this->conditions = array( -8 => array('token' => 'T_FOR',
                                              'atom' => 'none'),
                                   -7 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   -6 => array('atom' => 'yes'),
                                   -5 => array('token' => 'T_SEMICOLON'),
                                   -4 => array('atom' => 'yes'),
                                   -3 => array('token' => 'T_SEMICOLON'),
                                   -2 => array('atom' => 'yes'),
                                   -1 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                    0 => array('atom' => 'yes', 'notAtom' => 'Block',),
        );                
        $this->actions = array( 'to_block' => true);
        $this->checkAuto();

        $this->conditions = array( -7 => array('token' => 'T_FOR',
                                               'atom' => 'none'),
                                   -6 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   -5 => array('atom' => 'yes'),
                                   -4 => array('token' => 'T_SEMICOLON'),
                                   -3 => array('atom' => 'yes'),
                                   -2 => array('token' => 'T_SEMICOLON'),
                                   -1 => array('atom' => 'yes'),
                                    0 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                    1 => array('token' => 'T_SEMICOLON'),
        );
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'LEVEL')));
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>