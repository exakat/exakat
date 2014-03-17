<?php

namespace Tokenizer;

class _Static extends TokenAuto {
    static public $operators = array('T_STATIC');

    public function _check() {
        $values = array('T_EQUAL', 'T_COMMA');

    // class x { static function f() }
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   1 => array('token' => 'T_FUNCTION'),
                                 );
        $this->actions = array('to_option' => 1,
                               'atom'      => 'Static');
        $this->checkAuto(); 

    // class x { static public function x() }
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   2 => array('token' => array('T_FUNCTION')),
                                 );
        $this->actions = array('to_option' => 2,
                               'atom'      => 'Static');
        $this->checkAuto(); 

    // class x { static $x }
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   1 => array('atom' => array('Variable', 'String', 'Staticconstant', )), //'Function', 'Abstract', 'Final'
                                   2 => array('filterOut' => $values)
                                 );
        
        $this->actions = array('to_ppp'     => 1,
                               'atom'       => 'Static',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

    // class x { static private $x }
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   1 => array('atom' => 'Ppp'),
                                   2 => array('filterOut' => $values)
                                 );
        
        $this->actions = array('to_ppp2'    => 1,
                               'atom'       => 'Static',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

    // class x { static $x = 2 }
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   1 => array('atom' => 'Assignation'),
                                   2 => array('filterOut' => $values)
                                 );
        
        $this->actions = array('to_ppp_assignation' => 1,
                               'atom'               => 'Static', );
        $this->checkAuto(); 

    // class x { static $x, $y }
        $this->conditions = array(-1 => array('filterOut2' => array('T_NEW', 'T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC')),
                                   0 => array('token'      => _Static::$operators),
                                   1 => array('atom'       => 'Arguments'),
                                   2 => array('filterOut'  => 'T_COMMA'),
                                 );
        
        $this->actions = array('to_var_new' => 'Static',
                               'atom'       => 'Static',
                               );
        $this->checkAuto(); 

    // class x { private static $x, $y }
        $this->conditions = array(-1 => array('token' => array('T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC')),
                                   0 => array('token' => _Static::$operators),
                                   1 => array('atom'  => 'Arguments'),
                                   2 => array('filterOut'  => 'T_COMMA'),
                                 );
        
        $this->actions = array('to_var_ppp' => array('Static', 'Ppp'),
                               'atom'       => 'Static');
        $this->checkAuto(); 

    // static :: ....
        $this->conditions = array( 0 => array('token' => _Static::$operators),
                                   1 => array('token' => 'T_DOUBLE_COLON'),
                                 );

        $this->actions = array('atom'     => 'Static');
        $this->checkAuto(); 

    // static :: ....
        $this->conditions = array( -1 => array('token' => 'T_INSTANCEOF'),
                                   0  => array('token' => _Static::$operators),
                                 );
        $this->actions = array('atom'     => 'Static');
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        $ppp = new _Function(Token::$client);
        return $ppp->fullcode();
    }
}
?>