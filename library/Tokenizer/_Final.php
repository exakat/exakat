<?php

namespace Tokenizer;

class _Final extends TokenAuto {
    static public $operators = array('T_FINAL');

    function _check() {
    // abstract class x { abstract function x() }
        $this->conditions = array( 0 => array('token' => _Final::$operators),
                                   1 => array('atom' => array('Function', 'Ppp', 'Static', 'Abstract', 'Class', 'Static')),
                                 );
        
        $this->actions = array('transform' => array( 1 => 'FINAL'),
                               'atom'      => 'Final');

        $r = $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>