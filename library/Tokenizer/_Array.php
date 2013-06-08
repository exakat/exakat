<?php

namespace Tokenizer;

class _Array extends TokenAuto {
    function _check() {
        $this->conditions = array( 0 => array('atom' => array('Variable', 'Array')),
                                   1 => array('code' => '['),
                                   2 => array('atom' => 'yes'),
                                   3 => array('code' => ']'),
                                 );
        
        $this->actions = array('makeEdge'   => array( '2' => 'INDEX'),
                               'dropNext'   => array(1),
                               'atom'       => 'Array',
                               );

        $r = $this->checkAuto(); 

        return $r;
    }
}

?>