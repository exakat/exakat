<?php

namespace Tokenizer;

class _Final extends TokenAuto {
    static public $operators = array('T_FINAL');

    function _check() {
    // final class x { final function x() }
        $this->conditions = array( 0 => array('token' => _Final::$operators),
                                   1 => array('atom' => array('Function', 'Static', 'Abstract', 'Class', 'Static')),
                                 );
        $this->actions = array('to_ppp' => 1,
                               'atom'   => 'Final', );
        $this->checkAuto(); 

    // class x { final protected $x }
        $this->conditions = array( 0 => array('token' => _Final::$operators),
                                   1 => array('atom' => 'Ppp'),
                                 );
        
        $this->actions = array('to_ppp2' => 1,
                               'atom'   => 'Final', );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>