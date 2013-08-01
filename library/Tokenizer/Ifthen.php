<?php

namespace Tokenizer;

class Ifthen extends TokenAuto {
    static public $operators = array('T_IF', 'T_ELSEIF');

    function _check() {
    
    // @doc if then else
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => 'Block'),
                                   3 => array('token' => 'T_ELSE', 'atom' => 'none'),
                                   4 => array('atom' => array('Block', 'Ifthen')),
                                   5 => array('filterOut' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array('1' => 'CONDITION',
                                                       '2' => 'THEN',    
                                                       '3' => 'DROP',
                                                       '4' => 'ELSE'
                                                      ),
                               'atom'       => 'Ifthen',
                               );
        $r = $this->checkAuto(); 

    // @doc if then else
        $this->conditions = array( 0 => array('token' => Ifthen::$operators),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => 'Block'),
                                   3 => array('atom' => 'Ifthen'),
                                   4 => array('filterOut2' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array('1' => 'CONDITION',
                                                       '2' => 'THEN',    
                                                       '3' => 'ELSE'
                                                      ),
                               'atom'       => 'Ifthen',
                               );

        $r = $this->checkAuto(); 

    // @doc if then NO ELSE
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => 'Block'),
                                   3 => array('filterOut2' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array('1' => 'CONDITION',
                                                       '2' => 'THEN',    
                                                      ),
                               'atom'       => 'Ifthen',
                               );

        $r = $this->checkAuto(); 

    // @doc if ( ) : endif
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('token' => 'T_ENDIF'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',    
                                                        3 => 'THEN',    
                                                        4 => 'DROP', 
                                                      ),
                               'atom'       => 'Ifthen',
                               );
        $r = $this->checkAuto(); 

    // @doc if ( ) : else: endif
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('token' => 'T_ELSE'),
                                   5 => array('token' => 'T_COLON'),
                                   6 => array('atom' => 'yes'),
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
                               );

        $r = $this->checkAuto(); 

    // @doc if ( ) : elseif
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('atom' => 'Ifthen'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',    
                                                        3 => 'THEN', 
                                                        4 => 'ELSE',
                                                      ),
                               'atom'       => 'Ifthen',
                               );
        $r = $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>