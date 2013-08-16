<?php

namespace Tokenizer;

class _Const extends TokenAuto {
    static public $operators = array('T_CONST');

    function _check() {
    // class x {}
        $this->conditions = array( 0 => array('token' =>  _Const::$operators),
                                   1 => array('atom' => 'String'),
                                   2 => array('token' => 'T_EQUAL'),
                                   3 => array('atom' => array('String', 'Integer', 'Staticconstant', 'Sign' )),
                                 );
        
        $this->actions = array('transform'   => array(   1 => 'NAME',
                                                         2 => 'DROP',
                                                         3 => 'VALUE'),
                               'atom'        => 'Const',
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto(); 

    // class x { const a = 2; }
        $this->conditions = array( 0 => array('token' => _Const::$operators),
                                   1 => array('token' => 'T_COMMA'),
                                   2 => array('atom'  => 'Assignation')
                                 );
        
        $this->actions = array('transform'   => array(   1 => 'TO_CONST' ),
                               'atom'       => 'Const',
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>