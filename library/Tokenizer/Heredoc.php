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

s = [];
fullcode.out("CONCAT").sort{it.rank}._().each{ s.add(it.fullcode); };
fullcode.setProperty('noDelimiter', s.join(""));

if (fullcode.in('CONTAIN').next().code.substring(3, 4) in ["'"]) {
    // must get rid of ' in the nowdoc indicator
    fullcode.setProperty('fullcode', it.code + s.join("") + it.code.substring(4, it.code.size() - 2));
    fullcode.setProperty('nowdoc', 'true');
} else {
    fullcode.setProperty('heredoc', 'true');
    fullcode.setProperty('fullcode', it.code + s.join("") + it.code.substring(3, it.code.size()));
}

GREMLIN;
    }
}

?>
