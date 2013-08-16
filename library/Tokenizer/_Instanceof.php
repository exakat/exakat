<?php

namespace Tokenizer;

class _Instanceof extends TokenAuto {
    static public $operators = array('T_INSTANCEOF');

    function _check() {
        $this->conditions = array(-2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                  -1 => array('atom' => 'yes'),
                                   0 => array('token' => _Instanceof::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'yes'),
                                  
        );
        
        $this->actions = array('makeEdge'   => array(  1 => 'RIGHT',
                                                      -1 => 'LEFT'),
                               'atom'       => 'Instanceof',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    } 
}
?>