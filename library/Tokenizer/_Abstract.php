<?php

namespace Tokenizer;

class _Abstract extends TokenAuto {
    static public $operators = array('T_ABSTRACT');

    function _check() {
    // abstract class x { abstract function x() }
        $this->conditions = array( 0 => array('token' => _Abstract::$operators),
                                   1 => array('atom' => array('Class', 'Function', 'Static')),
                                 );
        $this->actions = array('to_ppp' => 1,
                               'atom'   => 'Abstract');
        $this->checkAuto(); 

    // class x { abstract protected $x }
        $this->conditions = array( 0 => array('token' => _Abstract::$operators),
                                   1 => array('atom' => 'Ppp'),
                                 );
        
        $this->actions = array('to_ppp2' => 1,
                               'atom'   => 'Abstract');
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>