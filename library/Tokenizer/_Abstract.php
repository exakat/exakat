<?php

namespace Tokenizer;

class _Abstract extends TokenAuto {
    static public $operators = array('T_ABSTRACT');

    function _check() {
    // abstract class x { abstract function x() }
        $this->conditions = array( 0 => array('token' => _Abstract::$operators),
                                   1 => array('atom' => array('Class', 'Function', 'Ppp', 'Static', )),
                                 );
        
        $this->actions = array('transform' => array( 1 => 'ABSTRACT'),
                               'atom'      => 'Abstract',);
        $r = $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>