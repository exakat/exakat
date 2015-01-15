<?php

namespace Tokenizer;

class Keyvalue extends TokenAuto {
    static public $operators = array('T_DOUBLE_ARROW');
    static public $atom = 'Keyvalue';

    public function _check() {
        $this->conditions = array(-2 => array('token' => array('T_OPEN_PARENTHESIS', 'T_COMMA', 'T_AS', 'T_OPEN_BRACKET', 'T_YIELD')),
                                  -1 => array('atom' => 'yes',
                                              'notAtom' => 'Arguments'),
                                   0 => array('token' => Keyvalue::$operators),
                                   1 => array('atom' => 'yes',
                                              'notAtom' => 'Arguments'),
                                   2 => array('token' => array('T_CLOSE_PARENTHESIS', 'T_COMMA', 'T_CLOSE_BRACKET', 'T_SEMICOLON')),
                                  );
        
        $this->actions = array('transform'  => array(-1 => 'KEY',
                                                      1 => 'VALUE'),
                               'atom'       => 'Keyvalue',
                               'cleanIndex' => true);
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out("KEY").next().getProperty('fullcode') + " => " + fullcode.out("VALUE").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
