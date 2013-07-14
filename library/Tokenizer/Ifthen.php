<?php

namespace Tokenizer;

class Ifthen extends TokenAuto {
    function _check() {
    
    // @doc if then else
        $this->conditions = array( 0 => array('token' => array('T_IF', 'T_ELSEIF'),
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
        $this->conditions = array( 0 => array('token' => array('T_IF', 'T_ELSEIF')),
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
        $this->conditions = array( 0 => array('token' => array('T_IF', 'T_ELSEIF'),
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

        return $r;
    }
}

?>