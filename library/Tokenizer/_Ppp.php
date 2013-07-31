<?php

namespace Tokenizer;

class _Ppp extends TokenAuto {
    static public $operators = array('T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC');

    function _check() {
        $values = array('T_EQUAL', 'T_COMMA');
    // class x { protected $x }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('atom' => array('Variable', 'String', 'Staticconstant', 'Static', 'Function', 'Abstract'  )),
                                   2 => array('filterOut' => $values),
                                   // T_SEMICOLON because of _Class 28 test
                                 );
        
        $this->actions = array('transform' => array( 1 => 'DEFINE'),
                               'add_void'  => array( 0 => 'VALUE'), 
                               'atom'      => 'Ppp',
                               );

        $this->checkAuto(); 

    // class x { var $x = 2 }
        $this->conditions = array( 0 => array('token' =>  _Ppp::$operators),
                                   1 => array('atom' => 'Assignation'),
                                   2 => array('token' => array('T_SEMICOLON')),
                                 );
        
        $this->actions = array('to_ppp' => true,
                               'atom'   => 'Ppp',
                               );

        $this->checkAuto(); 

    // class x { var $x, $y }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_COMMA')),
                                 );
        
        $this->actions = array('to_var'   => 'Ppp',
                               'atom'       => 'Ppp',
                               );

        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>