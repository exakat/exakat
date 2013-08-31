<?php

namespace Tokenizer;

class Ifthen extends TokenAuto {
    static public $operators = array('T_IF', 'T_ELSEIF');

    function _check() {
    
    // @doc if () with only ;
        $this->conditions = array( 0 => array('token' => Ifthen::$operators),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('token' => 'T_SEMICOLON', 'atom' => 'none')
        );
        
        $this->actions = array('addEdge'     => array(2 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'cleanIndex' => true);

        $this->checkAuto(); 
            
    // @doc if then else
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => 'Block'),
                                   3 => array('token' => 'T_ELSE', 'atom' => 'none'),
                                   4 => array('atom' => array('Block')),
                                   5 => array('filterOut' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'   => array(1 => 'CONDITION',
                                                      2 => 'THEN',    
                                                      3 => 'DROP',
                                                      4 => 'ELSE'),
                               'atom'       => 'Ifthen',
                               'cleanIndex' => true);
        $this->checkAuto(); 

    // @doc if then without else
        $this->conditions = array( 0 => array('token' => Ifthen::$operators),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => 'Block'),
                                   3 => array('atom' => 'Ifthen', 'token' => 'T_ELSEIF'),
                                   4 => array('filterOut2' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array('1' => 'CONDITION',
                                                       '2' => 'THEN',    
                                                       '3' => 'ELSE'
                                                      ),
                               'atom'       => 'Ifthen',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

        // Make a bloc from sequence after a if/elseif
        $this->conditions = array(  0 => array('token' => Ifthen::$operators,
                                               'atom' => 'none'),
                                    1 => array('atom' => 'Parenthesis'),
                                    2 => array('notAtom' => array('Block', 'Sequence'), 'atom' => 'yes'),
                                    3 => array('filterOut' => Token::$instruction_ending),
        );
        
        $this->actions = array( 'to_block_ifelseif' => true,
                                'keepIndexed'       => true);
        $this->checkAuto(); 

        // if, elseif followed by a single instruction without a ;
        $this->conditions = array(  0 => array('token' => Ifthen::$operators,
                                               'atom' => 'none'),
                                    1 => array('atom' => 'Parenthesis'),
                                    2 => array('atom' => array('For', 'Switch', 'Foreach', 'While', 'Dowhile', 'Ifthen' ))
        );
        
        $this->actions = array( 'to_block_ifelseif_instruction' => true,
                                'keepIndexed'       => true);
        $this->checkAuto(); 

    // @doc if then NO ELSE
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => 'Block'),
                                   3 => array('filterOut2' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'THEN'),
                               'atom'       => 'Ifthen');
        $this->checkAuto(); 

    // @doc if ( ) : endif
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('token' => 'T_ENDIF'),
        );
        
        $this->actions = array('transform'   => array( 1 => 'CONDITION',
                                                       2 => 'DROP',    
                                                       3 => 'THEN',    
                                                       4 => 'DROP'),
                               'atom'       => 'Ifthen',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

    // @doc if ( ) : else: endif
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => array('Block', 'Sequence', 'RawString', 'Void')),
                                   4 => array('token' => 'T_ELSE'),
                                   5 => array('token' => 'T_COLON'),
                                   6 => array('atom'  => array('Block', 'Sequence', 'RawString', 'Void')),
                                   7 => array('token' => array('T_ENDIF', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',    
                                                        3 => 'THEN',    
                                                        4 => 'DROP', 
                                                        5 => 'DROP', 
                                                        6 => 'ELSE', 
                                                        7 => 'DROP', 
                                                      ),
                               'atom'       => 'Ifthen',
                               'property'   => array('Alternative' => true),
                               'cleanIndex' => true
                               );

        $this->checkAuto(); 

    // @doc if ( ) : elseif
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => 'yes'), //array('Block', 'Sequence', 'RawString', 'Void')),
                                   4 => array('atom'  => 'Ifthen', 'token' => 'T_ELSEIF' ),
                                   5 => array('filterOut2' => 'T_ENDIF'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',    
                                                        3 => 'THEN', 
                                                        4 => 'ELSE',
                                                      ),
                               'atom'       => 'Ifthen',
                               'cleanIndex' => true
                               );
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>