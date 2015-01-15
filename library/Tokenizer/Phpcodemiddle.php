<?php

namespace Tokenizer;

class Phpcodemiddle extends TokenAuto {
    static public $operators = array('T_INLINE_HTML');

    public function _check() {
// ? >A<?php
        $this->conditions = array(-1 => array('token'    => 'T_CLOSE_TAG',
                                              'atom'     => 'none'),
                                   0 => array('token'    => Phpcodemiddle::$operators),
                                   1 => array('token'    => 'T_OPEN_TAG',
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
