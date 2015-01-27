<?php

namespace Tokenizer;

class ArgumentsNoComma extends Arguments {
    static public $operators = array('T_OPEN_PARENTHESIS');
    static public $atom = 'Arguments';

    public function _check() {
        // @note f(1) : no comma
        $this->conditions = array(-1 => array('token'     => array_merge(Functioncall::$operatorsWithoutEcho,
                                                                         array('T_FUNCTION', 'T_DECLARE', 'T_USE'))),
                                   0 => array('token'     => ArgumentsNoComma::$operators,
                                              'atom'      => 'none'),
                                   1 => array('atom'      => 'yes',
                                              'notAtom'   => 'Arguments'),
                                   2 => array('token'     => 'T_CLOSE_PARENTHESIS',
                                              'atom'      => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS')),
        );

        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'rank'        => array(1 => '0')
                               );
        $this->checkAuto();

       // @note f[1](2) : no comma
        $this->conditions = array(-1 => array('atom'     => 'Array'),
                                   0 => array('token'     => ArgumentsNoComma::$operators,
                                              'atom'      => 'none'),
                                   1 => array('atom'      => 'yes',
                                              'notAtom'   => 'Arguments'),
                                   2 => array('token'     => 'T_CLOSE_PARENTHESIS',
                                              'atom'      => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS')),
        );

        $this->actions = array('insertEdge'  => array(0 => array('Arguments' => 'ARGUMENT')),
                               'rank'        => array(1 => '0')
                               );
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
