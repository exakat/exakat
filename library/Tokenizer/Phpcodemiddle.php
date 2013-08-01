<?php

namespace Tokenizer;

class Phpcodemiddle extends TokenAuto {
    static public $operators = array('T_CLOSE_TAG');

    function _check() {
// ? >A<?php 
        $this->conditions = array(-1 => array('token' => array('T_CLOSE_TAG'),
                                              'atom' => 'none'),
                                   0 => array('atom' => 'yes'),
                                   1 => array('token' => 'T_OPEN_TAG',
                                              'atom' => 'none'),
        );
        $this->actions = array('transform'    => array( -1 => 'DROP',
                                                         1 => 'DROP',)
                              );
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>