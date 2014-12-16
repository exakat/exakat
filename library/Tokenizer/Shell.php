<?php

namespace Tokenizer;

class Shell extends TokenAuto {
    static public $operators = array('T_SHELL_QUOTE');
    static public $atom = 'Shell';

    public function _check() {
// Case of string with interpolation : `a${b}c`;
        $this->conditions = array(  0 => array('token'            => Shell::$operators, 
                                               'atom'             => 'none'),
                                    1 => array('atom'             => String::$allowedClasses,
                                               'check_for_string' => String::$allowedClasses),
                                 );
        
        $this->actions = array( 'make_quoted_string' => 'Shell');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        // fullcode is not meant to reproduce the whole code, but give a quick peek at some smaller code. Just ignoring for the moment.
        return <<<GREMLIN

fullcode.setProperty("fullcode", it.out('CONTAIN').next().fullcode);

GREMLIN;
    }
}
?>
