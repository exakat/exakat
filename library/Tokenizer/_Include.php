<?php

namespace Tokenizer;

class _Include extends TokenAuto {
    static public $operators = array('T_INCLUDE_ONCE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 'T_REQUIRE');

    public function _check() {
        // include( );
        $this->conditions = array(  0 => array('token' => _Include::$operators),
                                    1 => array('atom'  => 'none',
                                               'token' => 'T_OPEN_PARENTHESIS' ),
                                    2 => array('atom'  => 'Arguments'),
                                    3 => array('atom'  => 'none',
                                               'token' => 'T_CLOSE_PARENTHESIS' ),
                                    4 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON', 'T_EQUAL', 'T_DOT' )),
        );
        
        $this->actions = array('makeEdge'     => array('2' => 'ARGUMENTS'),
                               'dropNext'     => array(1),
                               'atom'         => 'Include',
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

        // include 'inclusion.php';
        $this->conditions = array( 0 => array('token' => _Include::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON', 'T_EQUAL' )),
        );
        
        $this->actions = array('makeEdge'     => array(1 => 'ARGUMENTS',),
                               'atom'         => 'Include',
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

if (fullcode.noParenthesis == 'true') {
    s = fullcode.out("ARGUMENTS").next().fullcode;
    fullcode.fullcode = it.code + " " + s.substring(1, s.length() - 1);
} else {
    fullcode.fullcode = fullcode.code + fullcode.out("ARGUMENTS").next().fullcode;
}

GREMLIN;
    }

}
?>