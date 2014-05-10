<?php

namespace Tokenizer;

class Phpcodemiddle extends TokenAuto {
    static public $operators = array('T_CLOSE_TAG');

    public function _check() {
// ? >A<?php 
        $this->conditions = array( 0 => array('token'    => Phpcodemiddle::$operators,
                                              'atom'     => 'none'),
                                   1 => array('atom'     =>  array('RawString', 'Sequence')),
                                   2 => array('token'    => 'T_OPEN_TAG',
                                              'atom'     => 'none'),
        );
        $this->actions = array('Phpcodemiddle' => true,
                               'makeSequence'  => 'c'
                               );
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>