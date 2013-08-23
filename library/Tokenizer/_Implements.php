<?php

namespace Tokenizer;

class _Implements extends TokenAuto {
    static public $operators = array('T_IMPLEMENTS');

    function _check() {
        // @note implements a,b (two only)
        $this->conditions = array( 0 => array('token' => _Implements::$operators ),
                                   1 => array('token' => 'T_STRING'),
                                   2 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   3 => array('token' => 'T_STRING')
                            );
        
        $this->actions = array('implement2arguments' => true,
                               'cleanIndex' => true
                               );
        $this->checkAuto();

/*
        // @note implements a,b,c (three or more)
        $this->conditions = array(-2 => array('token' => _Implements::$operators ),
                                  -1 => array('atom' => 'Arguments'),
                                   0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_STRING')
                            );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'    => array( 1 => '2',
                                                   -1 => '1'),
                               'mergeNext'  => array('Arguments' => 'ARGUMENT'), 
                               'atom'       => 'Arguments',
                               'cleanIndex' => true
                               );
        $this->checkAuto();
*/
        return $this->checkRemaining();
    }
}
?>