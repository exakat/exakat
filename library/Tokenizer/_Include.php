<?php

namespace Tokenizer;

class _Include extends TokenAuto {
    static public $operators = array('T_INCLUDE_ONCE','T_INCLUDE','T_REQUIRE_ONCE','T_REQUIRE');

    function _check() {
        $this->conditions = array(  0 => array('token' => _Include::$operators),
                                    1 => array('atom'  => 'none',
                                               'code'  => 'T_OPEN_PARENTHESIS' ),
                                    2 => array('atom'  => 'Arguments'),
                                    3 => array('atom'  => 'none',
                                               'token' => 'T_CLOSE_PARENTHESIS' ),
                                    4 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON', 'T_EQUAL' )),
        );
        
        $this->actions = array('makeEdge'    => array('2' => 'ARGUMENTS',),
                               'dropNext'   => array(1),
                               'atom'       => 'Include',
                               );
        $this->checkAuto();

        $this->conditions = array( 0 => array('token' => _Include::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON', 'T_EQUAL' )),
        );
        
        $this->actions = array('makeEdge'    => array(1 => 'ARGUMENTS',),
                               'atom'       => 'Include');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}
?>