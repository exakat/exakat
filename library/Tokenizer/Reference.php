<?php

namespace Tokenizer;

class Reference extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_AND',
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Variable', 'Array', 'Property')),
                                  2 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'REFERENCE'),
                               'atom'       => 'Reference');
        $r = $this->checkAuto();

        $this->conditions = array(-1 => array('token' => 'T_FUNCTION',
                                             'atom' => 'none'),
                                  0 => array('token' => 'T_AND'),
                                  1 => array('atom' => 'String'),
                                  2 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  3 => array('atom' => 'Arguments'),
                                  4 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  5 => array('atom' => 'Block'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'REFERENCE'),
                               'atom'       => 'Reference');
                               
        $r = $this->checkAuto();
                               
        return $r;
    }
}

?>