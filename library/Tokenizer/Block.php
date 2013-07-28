<?php

namespace Tokenizer;

class Block extends TokenAuto {
    function _check() {
    
// @doc empty block
        $this->conditions = array( 0 => array('token' => 'T_OPEN_CURLY',
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_CLOSE_CURLY',
                                              'atom' => 'none'),
        );

        $this->actions = array('addEdge'   => array(0 => array('Void' => 'CODE')));
        $r = $this->checkAuto(); 

    // @doc Block
        $this->conditions = array( -1 => array('filterOut2' => array('T_VARIABLE', 'T_DOLLAR', 'T_CLOSE_CURLY', 'T_OPEN_BRACKET', 'T_CLOSE_BRACKET' )),
                                   0 => array('token' => 'T_OPEN_CURLY',
                                              'atom' => 'none'),
                                   1 => array('atom' => 'yes'),
                                   2 => array('token' => 'T_CLOSE_CURLY',
                                              'atom' => 'none'),
                                   
        );
        
        $this->actions = array('transform'    => array(1 => 'CODE',
                                                       2 => 'DROP',
                                                      ),
                               'atom'       => 'Block',
                               );
        $r = $this->checkAuto(); 

    // @doc Block in a switch/case/default
        $this->conditions = array( -3 => array('token' => 'T_CASE',
                                              'atom' => 'none'),
                                   -2 => array('atom' => 'yes'),
                                   -1 => array('token' => 'T_COLON',
                                              'atom' => 'none'),
                                    0 => array('atom' => 'Sequence'),
                                    1 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT')),
        );
        
        $this->actions = array('createBlockWithSequence'    => true);
        $r = $this->checkAuto(); 

        $this->conditions = array( -2 => array('token' => 'T_DEFAULT',
                                              'atom' => 'none'),
                                   -1 => array('token' => 'T_COLON',
                                              'atom' => 'none'),
                                    0 => array('atom' => 'Sequence'),
                                    1 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT',)),
        );
        
        $this->actions = array('createBlockWithSequence'    => true);
        $r = $this->checkAuto(); 

        $this->conditions = array( -2 => array('token' => array('T_IF', 'T_ELSEIF'),
                                               'atom' => 'none'),
                                   -1 => array('atom' => 'Parenthesis'),
                                    0 => array('notAtom' => 'Block', 'atom' => 'yes', ),
                                    1 => array('token' => 'T_SEMICOLON')
        );
        
        $this->actions = array( 'to_block' => true);
        $r = $this->checkAuto(); 

        $this->conditions = array( -2 => array('token' => array('T_IF', 'T_ELSEIF'),
                                               'atom' => 'none'),
                                   -1 => array('atom' => 'Parenthesis'),
                                    0 => array('atom' => array('For', 'Switch', 'Foreach', 'While', 'Dowhile', ))
        );
        
        $this->actions = array( 'to_block' => true);
        $r = $this->checkAuto(); 

        $this->conditions = array( -1 => array('token' => 'T_ELSE',
                                               'atom' => 'none'),
                                    0 => array('notAtom' => 'Block', 'atom' => 'yes', ),
                                    1 => array('token' => 'T_SEMICOLON')
        );
        
        $this->actions = array( 'to_block' => true);
        $r = $this->checkAuto(); 

        $this->conditions = array(-5 => array('filterOut2' => array('T_CLOSE_CURLY', 'T_OPEN_CURLY')),
                                  -4 => array('token' => 'T_WHILE'),
                                  -3 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  -2 => array('atom'  => 'yes'),
                                  -1 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   0 => array('token' => 'T_SEMICOLON', 'atom' => 'none'),
        );
        
        $this->actions = array('transform'    => array(1 => 'BLOCK'),
                                'atom' => 'Block');
        $r = $this->checkAuto();

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
        $r = $this->checkAuto();

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
        $r = $this->checkAuto();

        return $r;
    }
}

?>