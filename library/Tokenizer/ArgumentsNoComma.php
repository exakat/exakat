<?php

namespace Tokenizer;

class ArgumentsNoComma extends Arguments {
    static public $operators = array('T_OPEN_PARENTHESIS');

    public function _check() {
        
        // @note f(1) : no comma 
        $this->conditions = array(-1 => array('token' => array_merge(Functioncall::$operators_without_echo, 
                                                         array('T_FUNCTION', 'T_DECLARE', 'T_USE'))),
                                   0 => array('token' => ArgumentsNoComma::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => Arguments::$operands_wa),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLECOLON', 'T_OPEN_PARENTHESIS')), //, 'T_OBJECT_OPERATOR'
        );

        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));
        $this->checkAuto();

        $this->conditions = array(-1 => array('token' => 'T_ECHO'),
                                   0 => array('token' => ArgumentsNoComma::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => Arguments::$operands_wa),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLECOLON', 'T_OPEN_PARENTHESIS', 'T_COMMA', 'T_OBJECT_OPERATOR', 'T_DOT',)),
        );

        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));
        $this->checkAuto();

        // @note require (1).$d : no comma 
        $this->conditions = array(-1 => array('token' => _Include::$operators),
                                   0 => array('token' => ArgumentsNoComma::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => Arguments::$operands_wa),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLECOLON', 'T_OPEN_PARENTHESIS', 'T_DOT')),
        );

        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));
        $this->checkAuto();

        // @note a->{f}(1) : no comma 
        $this->conditions = array(-4 => array('token' => 'T_OBJECT_OPERATOR'),
                                  -3 => array('token' => 'T_OPEN_CURLY'),
                                  -2 => array('atom' => 'yes'),
                                  -1 => array('token' => 'T_CLOSE_CURLY'),
                                   0 => array('token' => ArgumentsNoComma::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => Arguments::$operands_wa),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLECOLON', 'T_OPEN_PARENTHESIS')),
        );

        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));
        $this->checkAuto();

        // echo $e
        $this->conditions = array(-1 => array('token' => array('T_PRINT', 'T_ECHO')),
                                   0 => array('token' => ArgumentsNoComma::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => Arguments::$operands_wa),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut2' => array_merge(array('T_COMMA'), Token::$instruction_ending)),
        );

        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));
        $this->checkAuto();

        return $this->checkRemaining();
    }
}
?>