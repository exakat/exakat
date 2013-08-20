<?php

namespace Tokenizer;

class _Static extends TokenAuto {
    static public $operators = array('T_STATIC');

    function _check() {
        $values = array('T_EQUAL', 'T_COMMA');

    // class x { static $x }
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   1 => array('atom' => array('Variable', 'String', 'Staticconstant', 'Ppp', 'Function', 'Abstract', 'Final', 'Assignation' )),
                                   2 => array('filterOut' => $values)
                                 );
        
        $this->actions = array('transform' => array( 1 => 'DEFINE'),
                               'add_void'  => array( 0 => 'VALUE'), 
                               'atom'      => 'Static',
                               'cleanIndex' => true
                               );

        $this->checkAuto(); 

    // class x { static $x, $y }
        $this->conditions = array(-1 => array('filterOut2' => array('T_NEW')),
                                   0 => array('token' => _Static::$operators),
                                   1 => array('atom' => 'Arguments'),
                                 );
        
        $this->actions = array('to_var'   => 'Static',
                               'atom'     => 'Static',
                               );

        $this->checkAuto(); 

    // static :: ....
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   1 => array('token' => 'T_DOUBLE_COLON'),
                                 );
        
        $this->actions = array('atom'     => 'Static');

        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>