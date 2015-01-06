<?php

namespace Tokenizer;

class _Ppp extends TokenAuto {
    static public $operators = array('T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC');
    static public $atom = 'Visibility';

    public function _check() {
        $values = array('T_EQUAL', 'T_COMMA');
    // class x { protected $x }
        $this->conditions = array( -1 => array('filterOut2' =>  'T_STATIC'),
                                    0 => array('token'      => _Ppp::$operators),
                                    1 => array('atom'       => array('Variable', 'String', 'Staticconstant')),
                                    2 => array('filterOut'  => $values),
                                 );
        $this->actions = array('to_ppp'       => 1,
                               'atom'         => 'Ppp',
                               'makeSequence' => 'x' );
        $this->checkAuto(); 

    // class x { static private $s }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('token' => 'T_STATIC'),
                                   2 => array('token' => 'T_VARIABLE'),
                                 );
        $this->actions = array('to_option' => 1,
                               'atom'      => 'Ppp');
        $this->checkAuto(); 

    // class x { public $x = 2 }
        $this->conditions = array(-1 => array('filterOut2' =>  'STATIC'),
                                   0 => array('token'      =>  _Ppp::$operators),
                                   1 => array('atom'       => 'Assignation'),
                                   2 => array('token'      => array('T_SEMICOLON')),
                                 );
        
        $this->actions = array('to_ppp_assignation' => true,
                               'atom'               => 'Ppp',
                               'makeSequence'       => 'x' 
                               );

        $this->checkAuto(); 

    // class x { public static $x = 2; }
        $this->conditions = array(-1 => array('filterOut2' =>  'STATIC'),
                                   0 => array('token'      =>  _Ppp::$operators),
                                   1 => array('atom'       => 'Assignation'),
                                   2 => array('token'      => array('T_SEMICOLON')),
                                 );
        
        $this->actions = array('to_ppp_assignation' => true,
                               'atom'               => 'Ppp',
                               'makeSequence'       => 'x' 
                               );

        $this->checkAuto(); 


    // class x { protected function f()  }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('to_option' => 1,
                               'atom'      => 'Ppp');
        $this->checkAuto(); 

    // class x { protected private function f()  }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('token' => array('T_ABSTRACT', 'T_FINAL', 'T_STATIC')),
                                   2 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('to_option' => 2,
                               'atom'      => 'Ppp');
        $this->checkAuto(); 

    // class x { protected private static function f()  }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('token' => array('T_ABSTRACT', 'T_FINAL', 'T_STATIC')),
                                   2 => array('token' => array('T_ABSTRACT', 'T_FINAL', 'T_STATIC')),
                                   3 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('to_option' => 3,
                               'atom'      => 'Ppp');
        $this->checkAuto(); 

    // class x { public $x, $y }
        $this->conditions = array(-1 => array('filterOut2' => 'T_STATIC'),
                                   0 => array('token'      => _Ppp::$operators),
                                   1 => array('atom'       => 'Arguments'),
                                   2 => array('filterOut'  => array('T_COMMA')),
                                 );
        
        $this->actions = array('to_var_new' => 'Ppp',
                               'atom'       => 'Ppp',
                               );
        $this->checkAuto(); 

    // class x { static private $x, $y }
        $this->conditions = array( 0 => array('token' => _Ppp::$operators),
                                   1 => array('token' => _Static::$operators),
                                   2 => array('atom'  => 'Arguments'),
                                 );
        
        $this->actions = array('to_option' => 1,
                               'atom'      => 'Ppp');
        $this->checkAuto(); 

        return false;
    }

    public function fullcode() {
        $token = new _Function(Token::$client);
        return $token->fullcode();
    }
}
?>
