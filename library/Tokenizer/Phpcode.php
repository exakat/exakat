<?php

namespace Tokenizer;

class Phpcode extends TokenAuto {
    function check() {
        print __METHOD__."\n";
        $this->conditions = array(0 => array('token' => 'T_OPEN_TAG',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_CLOSE_TAG'),
                                  
                                  
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'CODE'),
                               'dropNext'   => array(2), 
                               'atom'       => 'Phpcode');
//        $this->printQuery();    
        $r = $this->checkAuto();
        return $r;
    }
}

?>