<?php

namespace Tokenizer;

class _Var extends TokenAuto {
    static public $operators = array('T_VAR');

    function _check() {
        $values = array('T_EQUAL', 'T_COMMA');
    // class x { var $x }
        $this->conditions = array( 0 => array('token' => _Var::$operators),
                                   1 => array('atom' => array('Variable', 'String', 'Staticconstant', 'Static' )),
                                   2 => array('filterOut' => $values),
                                   // T_SEMICOLON because of _Class 28 test
                                 );
        
        $this->actions = array('transform' => array( 1 => 'DEFINE'),
                               'add_void'  => array( 0 => 'VALUE'), 
                               'atom'      => 'Var',
                               );

        $this->checkAuto(); 

    // class x { var $x = 2 }
        $this->conditions = array( 0 => array('token' => _Var::$operators),
                                   1 => array('atom' => 'Assignation'),
                                   2 => array('token' => array('T_SEMICOLON')),
                                 );
        
        $this->actions = array('to_ppp' => true,
                               'atom'   => 'Var',
                               );

        $this->checkAuto(); 

    // class x { var $x, $y }
        $this->conditions = array( 0 => array('token' => _Var::$operators),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_COMMA')),
                                 );
        
        $this->actions = array('to_var'   => 'Var',
                               'atom'       => 'Var',
                               );

        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>