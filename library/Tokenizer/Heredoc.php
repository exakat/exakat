<?php

namespace Tokenizer;

class Heredoc extends TokenAuto {
    static public $operators = array('T_START_HEREDOC');
    
    function _check() {
        $this->conditions = array(0 => array('token' => Heredoc::$operators,
                                               'atom' => 'none'),
                                  1 => array('atom'  => array('String', 'Variable', 'Concatenation', 'Array')),
                                 );

        $this->actions = array( 'make_quoted_string' => 'Heredoc');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>