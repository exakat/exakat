<?php

namespace Tokenizer;

class _Implements extends TokenAuto {
    static public $operators = array('T_IMPLEMENTS');

    public function _check() {
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

        return false;
    }
}
?>