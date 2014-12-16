<?php

namespace Tokenizer;

class Heredoc extends TokenAuto {
    static public $operators = array('T_START_HEREDOC');
    static public $atom = 'String';
    
    public function _check() {
        $this->conditions = array(0 => array('token'            => Heredoc::$operators,
                                             'atom'             => 'none'),
                                  1 => array('atom'             => String::$allowedClasses,
                                             'check_for_string' => String::$allowedClasses),
                                 );

        $this->actions = array( 'make_quoted_string' => 'Heredoc');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.getProperty('code')); 
if (fullcode.code.substring(3, 4) in ["'"]) {
    fullcode.setProperty('nowdoc', 'true'); 
} else {
    fullcode.setProperty('heredoc', 'true'); 
}

GREMLIN;
    }
}

?>
