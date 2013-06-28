<?php

namespace Tokenizer;

class Functioncall extends TokenAuto {
    function _check() {
        
        $this->conditions = array(  -1 => array('filterOut' => array('T_FUNCTION')),
                                    0 => array('token' => array('T_STRING', 'T_ECHO', 'T_UNSET', 'T_EMPTY', 'T_ARRAY', 'T_NS_SEPARATOR', 'T_VARIABLE', 'T_PRINT', 'T_ISSET', 'T_LIST', )),
                                    1 => array('atom' => 'none',
                                               'code' => '(' ),
                                    2 => array('atom' => 'Arguments'),
                                    3 => array('atom' => 'none',
                                              'code' => ')' ),
                                    4 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON')),
        );
        
        $this->actions = array('makeEdge'    => array('2' => 'ARGUMENTS',),
                               'dropNext'   => array(1),
                               'atom'       => 'Functioncall',
                               );
        $r = $this->checkAuto();

        $this->conditions = array( 0 => array('token' => array('T_ECHO', 'T_PRINT'),
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLECOLON')),
        );
        
        $this->actions = array('makeEdge'    => array('1' => 'ARGUMENTS',),
                               'atom'       => 'Functioncall',
                               );
        $r = $this->checkAuto();
        
        return $r;
    }
}
?>