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
        $this->conditions = array( -1 => array('filterOut2' => array('T_VARIABLE', 'T_DOLLAR')),
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
                                    1 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT')),
        );
        
        $this->actions = array('createBlockWithSequence'    => true);
        $r = $this->checkAuto(); 

        $this->conditions = array( -2 => array('token' => 'T_DEFAULT',
                                              'atom' => 'none'),
                                   -1 => array('token' => 'T_COLON',
                                              'atom' => 'none'),
                                    0 => array('atom' => 'Sequence'),
                                    1 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT')),
        );
        
        $this->actions = array('createBlockWithSequence'    => true);
        $r = $this->checkAuto(); 
        
        return $r;
    }
}

?>