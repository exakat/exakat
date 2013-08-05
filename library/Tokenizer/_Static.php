<?php

namespace Tokenizer;

class _Static extends TokenAuto {
    static public $operators = array('T_STATIC');

    function _check() {
        $values = array('T_EQUAL', 'T_COMMA');

    // class x { static $x }
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   1 => array('atom' => array('Variable', 'String', 'Staticconstant', 'Ppp', 'Function', 'Abstract', 'Final' )),
                                   2 => array('filterOut' => $values),
                                   // T_SEMICOLON because of _Class 28 test
                                 );
        
        $this->actions = array('transform' => array( 1 => 'DEFINE'),
                               'add_void'  => array( 0 => 'VALUE'), 
                               'atom'      => 'Static',
                               );

        $this->checkAuto(); 

    // class x { static $x = 2 }
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   1 => array('atom' => 'Assignation'),
                                   2 => array('token' => array('T_SEMICOLON')),
                                 );
        
        $this->actions = array('to_ppp' => true,
                               'atom'   => 'Static',
                               );

        $this->checkAuto(); 

    // class x { static $x, $y }
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   1 => array('atom' => 'Arguments'),
                                 );
        
        $this->actions = array('to_var'   => 'Static',
                               'atom'       => 'Static',
                               );

        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>