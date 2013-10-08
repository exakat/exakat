<?php

namespace Tokenizer;

class _Ppp extends TokenAuto {
    static public $operators = array('T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC');

    function _check() {
        $values = array('T_EQUAL', 'T_COMMA');
    // class x { protected $x }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('atom' => array('Variable', 'String', 'Staticconstant', 'Function', 'Abstract', 'Final', )),
                                   2 => array('filterOut' => $values),
                                   // T_SEMICOLON because of _Class 28 test
                                 );
        
        $this->actions = array('to_ppp' => 1,
                               'atom'   => 'Ppp', );
        $this->checkAuto(); 

    // class x { protected static $x }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('atom' => 'Ppp'),
                                   2 => array('filterOut' => $values),
                                   // T_SEMICOLON because of _Class 28 test
                                 );
        
        $this->actions = array('to_ppp2' => 1,
                               'atom'   => 'Ppp', );
        $this->checkAuto(); 

    // class x { var $x = 2 }
        $this->conditions = array( 0 => array('token' =>  _Ppp::$operators),
                                   1 => array('atom'  => 'Assignation'),
                                   2 => array('token' => array('T_SEMICOLON')),
                                 );
        
        $this->actions = array('to_ppp_assignation' => true,
                               'atom'   => 'Ppp',
                               );

        $this->checkAuto(); 

    // class x { var $x, $y }
        $this->conditions = array(-1 => array('filterOut2' => array('T_STATIC')),
                                   0 => array('token' => _Ppp::$operators),
                                   1 => array('atom' => 'Arguments'),
                                   2 => array('filterOut' => array('T_COMMA')),
                                 );
        
        $this->actions = array('to_var_new' => 'Ppp',
                               'atom'       => 'Ppp',
                               );
        $this->checkAuto(); 

    // class x { static private $x, $y }
        $this->conditions = array(-1 => array('token' => array('T_STATIC')),
                                   0 => array('token' => _Ppp::$operators),
                                   1 => array('atom'  => 'Arguments'),
                                 );
        
        $this->actions = array('to_var_ppp' => array('Ppp', 'Static'),
                               'atom'       => 'Ppp');
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}
?>