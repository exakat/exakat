<?php

namespace Tokenizer;

class Ifthen extends TokenAuto {
    function _check() {
    
    // @doc if then else
        $this->conditions = array( 0 => array('token' => 'T_IF',
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => 'Block'),
                                   3 => array('token' => 'T_ELSE', 'atom' => 'none'),
                                   4 => array('atom' => 'Block'),
        );
        
        $this->actions = array('transform'    => array('1' => 'CONDITION',
                                                       '2' => 'THEN',    
                                                       '3' => 'DROP',
                                                       '4' => 'ELSE'
                                                      ),
                               'atom'       => 'Ifthen',
                               );

        $r = $this->checkAuto(); 

    // @doc if then NO ELSE
        $this->conditions = array( 0 => array('token' => 'T_IF',
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => 'Block'),
                                   3 => array('filterOut' => 'T_ELSE'),
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