<?php

namespace Tokenizer;

class ArgumentsNoComma extends Arguments {
    static public $operators = array('T_OPEN_PARENTHESIS');
    static public $atom = 'Arguments';

    public function _check() {
        
        // @note f(1) : no comma 
        $this->conditions = array(-1 => array('token' => array_merge(Functioncall::$operators_without_echo, 
                                                         array('T_FUNCTION', 'T_DECLARE', 'T_USE'))),
                                   0 => array('token' => ArgumentsNoComma::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'yes',
                                              'notAtom' => 'Arguments'),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS')), 
        );

        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'rank'        => array(1 => '0')
                               );
        $this->checkAuto();

        // @note echo (1) : no comma 
        $this->conditions = array(-1 => array('token' => array('T_ECHO', 'T_PRINT')),
                                   0 => array('token' => ArgumentsNoComma::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'yes',
                                              'notAtom' => 'Arguments'),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array_merge(array('T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS', 'T_COMMA', 
                                                                               'T_OBJECT_OPERATOR', 'T_DOT', 'T_QUESTION'),
                                                                        Multiplication::$operators, Addition::$operators,
                                                                        Comparison::$operators, Logical::$operators,
                                                                        Bitshift::$operators, Power::$operators)),
        );

        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'rank'        => array(1 => '0'));
        $this->checkAuto();

        // @note require (1).$d : no comma 
        $this->conditions = array(-1 => array('token' => _Include::$operators),
                                   0 => array('token' => ArgumentsNoComma::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'yes',
                                              'notAtom' => 'Arguments'),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS', 'T_DOT')),
        );

        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'rank'        => array(1 => '0'));
        $this->checkAuto();

        // @note a->{f}(1) : no comma 
        $this->conditions = array(-4 => array('token' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                  -3 => array('token' => 'T_OPEN_CURLY'),
                                  -2 => array('atom' => 'yes'),
                                  -1 => array('token' => 'T_CLOSE_CURLY'),
                                   0 => array('token' => ArgumentsNoComma::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'yes',
                                              'notAtom' => array('Arguments', 'Void')),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS')),
        );

        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'rank'        => array(1 => '0'));
        $this->checkAuto();

        return false;
    }
}
?>