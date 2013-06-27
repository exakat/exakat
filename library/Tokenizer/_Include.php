<?php

namespace Tokenizer;

class _Include extends TokenAuto {
    function _check() {
        
        $this->conditions = array(  0 => array('token' => array('T_INCLUDE_ONCE','T_INCLUDE','T_REQUIRE_ONCE','T_REQUIRE',)),
                                    1 => array('atom' => 'none',
                                               'code' => '(' ),
                                    2 => array('atom' => 'Arguments'),
                                    3 => array('atom' => 'none',
                                              'code' => ')' ),
                                    4 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON', 'T_EQUAL' )),
        );
        
        $this->actions = array('makeEdge'    => array('2' => 'ARGUMENTS',),
                               'dropNext'   => array(1),
                               'atom'       => 'Include',
                               );
        $r = $this->checkAuto();

        $this->conditions = array( 0 => array('token' => array('T_INCLUDE_ONCE','T_INCLUDE','T_REQUIRE_ONCE','T_REQUIRE'),
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON', 'T_EQUAL' )),
        );
        
        $this->actions = array('makeEdge'    => array('1' => 'ARGUMENTS',),
                               'atom'       => 'Include',
                               );
        $r = $this->checkAuto();
        
        return $r;
    }
}
?>