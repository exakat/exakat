<?php

namespace Tokenizer;

class _Array extends TokenAuto {
    function check() {
        $this->conditions = array( 0 => array('atom' => array('Variable', 'Array')),
                                   1 => array('code' => '['),
                                   3 => array('code' => ']'),
                                 );
        
        $this->actions = array('makeEdge'   => array( '1' => 'INDEX'),
                               'dropNext'   => array(1, 2),
                               'atom'       => 'Array',
                               );
                               
//        $this->printQuery();
        $r = $this->checkAuto(); 

        return $r;
    }
}

?>