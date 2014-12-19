<?php

namespace Tokenizer;

class Phpcodemiddle extends TokenAuto {
    static public $operators = array('T_INLINE_HTML');

    public function _check() {
// ? >A<?php 
        $this->conditions = array( 0 => array('token'    => Phpcodemiddle::$operators,
                                              'atom'     => 'none'),
                                   1 => array('atom'     =>  array('RawString', 'Sequence')),
                                   2 => array('token'    => 'T_OPEN_TAG',
                                              'atom'     => 'none'),
        );
        $this->actions = array('transform'           => array( -1 => 'DROP',
                                                                1 => 'DROP'),
                               'makeSequence'        => 'it',
                               'makeSequenceAlways'  => true
                               );
        $this->checkAuto();
        
        return false;
    }
}

?>
