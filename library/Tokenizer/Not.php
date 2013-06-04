<?php

namespace Tokenizer;

class Not extends TokenAuto {
    function check() {
        $this->conditions = array(0 => array('token' => '!',
                                             'atom' => 'none')
                                  
        );
        
        $this->actions = array('addEdge'    => array( '1' => 'NOT'),
                               'changeNext' => array(1),
                               'atom'       => 'Not');

        return $this->checkAuto();
    }
}

?>