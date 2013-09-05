<?php

namespace Tokenizer;

class _Const extends TokenAuto {
    static public $operators = array('T_CONST');

    function _check() {
    // class x { const a = 2, b = 2, c = 3; }
        $this->conditions = array( 0 => array('token' => _Const::$operators),
                                   1 => array('atom'  => 'Arguments'),
                                   2 => array('filterOut' => 'T_COMMA'),
                                 );
        
        $this->actions = array('to_const'   => true,
//                               'atom'       => 'Const',
//                               'keepIndexed' => true,
//                               'cleanIndex'  => true
);
        $this->checkAuto(); 

    // class x {const a = 2; } only one.
        $this->conditions = array( 0 => array('token' =>  _Const::$operators),
                                   1 => array('atom'  => 'Assignation'),
                                   2 => array('token' => 'T_SEMICOLON')
                                 );
        
        $this->actions = array('to_const_assignation' => true,
                               'atom'        => 'Const',
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>