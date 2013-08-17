<?php

namespace Tokenizer;

class Phpcodemiddle extends TokenAuto {
    static public $operators = array('T_CLOSE_TAG');

    function _check() {
// ? >A<?php 
        $this->conditions = array( 0 => array('token' => Phpcodemiddle::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'RawString'),
                                   2 => array('token' => 'T_OPEN_TAG',
                                              'atom' => 'none'),
        );
        $this->actions = array('Phpcodemiddle'    => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>