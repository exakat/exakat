<?php

namespace Tokenizer;

class _Include extends TokenAuto {
    static public $operators = array('T_INCLUDE_ONCE', 'T_INCLUDE', 'T_REQUIRE_ONCE', 'T_REQUIRE');
    static public $atom = 'Include';

    public function _check() {
        // include( );
        $this->conditions = array(  0 => array('token' => _Include::$operators),
                                    1 => array('atom'  => 'none',
                                               'token' => 'T_OPEN_PARENTHESIS' ),
                                    2 => array('atom'  => 'Arguments'),
                                    3 => array('atom'  => 'none',
                                               'token' => 'T_CLOSE_PARENTHESIS' ),
                                    4 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_EQUAL', 'T_DOT' )),
        );
        
        $this->actions = array('transform'    => array('1' => 'DROP',
                                                       '2' => 'ARGUMENTS',
                                                       '3' => 'DROP'),
                               'atom'         => 'Include',
                               'makeSequence' => 'it',
                               'property'     => array('parenthesis' => 'true'),
                               );
        $this->checkAuto();

        // include 'inclusion.php';
        $this->conditions = array( 0 => array('token' => _Include::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_EQUAL' )),
        );
        
        $this->actions = array('transform'    => array(1 => 'ARGUMENTS',),
                               'atom'         => 'Include',
                               'makeSequence' => 'it',
                               'property'     => array('parenthesis' => 'false'),);
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (fullcode.getProperty('parenthesis') == 'true') {
    fullcode.setProperty('fullcode', fullcode.getProperty('code') + "(" + fullcode.out("ARGUMENTS").next().getProperty('fullcode') + ")");
} else {
    s = fullcode.out("ARGUMENTS").next().getProperty('fullcode');
    fullcode.setProperty('fullcode', it.getProperty('code') + " " + s );
}

GREMLIN;
    }

}
?>