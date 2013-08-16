<?php

namespace Tokenizer;

class _For extends TokenAuto {
    static public $operators = array('T_FOR');

    function _check() {
        // for (;;) ; (Empty loop)
        $this->conditions = array(  0 => array('token' => _For::$operators,
                                               'atom' => 'none'),
                                    1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                    2 => array('atom' => 'yes'),
                                    3 => array('token' => 'T_SEMICOLON'),
                                    4 => array('atom' => 'yes'),
                                    5 => array('token' => 'T_SEMICOLON'),
                                    6 => array('atom' => 'yes'),
                                    7 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                    8 => array('token' => 'T_SEMICOLON'),
        );
        $this->actions = array('addEdge'     => array(8 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'cleanIndex' => true);
        $this->checkAuto();

        // for (;;) $x++; (one line instruction, with or without )
        $this->conditions = array(  0 => array('token' => _For::$operators,
                                               'atom' => 'none'),
                                    1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                    2 => array('atom' => 'yes'),
                                    3 => array('token' => 'T_SEMICOLON'),
                                    4 => array('atom' => 'yes'),
                                    5 => array('token' => 'T_SEMICOLON'),
                                    6 => array('atom' => 'yes'),
                                    7 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                    8 => array('atom' => 'yes'),
                                    9 => array('filterOut2' => array_merge(array('T_OPEN_BRACKET', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON'), 
                                                                Assignation::$operators, Addition::$operators, Multiplication::$operators)),
        );                
        $this->actions = array( 'to_block_for' => true,
                                'keepIndexed'  => true,
                                'cleanIndex'   => true);
        $this->checkAuto();
    
    // @doc for(a; b; c) { code }
        $this->conditions = array( 0 => array('token' => _For::$operators,
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
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

    // @doc for(a; b; c) : code endfor
        $this->conditions = array( 0  => array('token' => _For::$operators,
                                               'atom' => 'none'),
                                   1  => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2  => array('atom' => 'yes'),
                                   3  => array('token' => 'T_SEMICOLON'),
                                   4  => array('atom' => 'yes'),
                                   5  => array('token' => 'T_SEMICOLON'),
                                   6  => array('atom' => 'yes'),
                                   7  => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   8  => array('token' => 'T_COLON'),
                                   9  => array('atom' => 'yes'),
                                   10 => array('token' => 'T_ENDFOR'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'INIT',    
                                                        3 => 'DROP',
                                                        4 => 'FINAL',
                                                        5 => 'DROP',
                                                        6 => 'INCREMENT',
                                                        7 => 'DROP',
                                                        8 => 'DROP',
                                                        9 => 'CODE',
                                                       10 => 'DROP', 
                                                      ),
                               'atom'       => 'For',
                               'property' => array('Alternative' => 'yes'),
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>