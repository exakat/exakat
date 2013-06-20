<?php

namespace Tokenizer;

class Block extends TokenAuto {
    function _check() {
    
    // @doc Block
        $this->conditions = array( 0 => array('token' => 'T_OPEN_CURLY',
                                              'atom' => 'none'),
                                   1 => array('atom' => 'yes'),
                                   2 => array('token' => 'T_CLOSE_CURLY',
                                              'atom' => 'none'),
                                   
        );
        
        $this->actions = array('makeEdge'    => array('1' => 'CODE',
                                                      ),
                               'dropNext'    => array('1'),
                               'atom'       => 'Block',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }
}

?>