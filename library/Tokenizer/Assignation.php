<?php

namespace Tokenizer;

class Assignation extends TokenAuto {
    function _check() {

        $this->conditions = array(0 => array('code' => array('='),
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Integer', 'Multiplication')),
                                  
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'),
                               'atom'       => 'Assignation',
                               'cleansemicolon' => 1);

        return $this->checkAuto();
    } 
}
?>