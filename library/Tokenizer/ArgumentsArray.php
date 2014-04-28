<?php

namespace Tokenizer;

class ArgumentsArray extends Arguments {
    static public $operators = array('T_OPEN_PARENTHESIS');
    static public $atom = 'Arguments';
    
    public function _check() {
        $this->conditions = array(-1 => array('atom'  => 'Array'),
                                   0 => array('token' => ArgumentsArray::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => Arguments::$operands_wa),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('filterOut' => array('T_DOUBLECOLON', 'T_OPEN_PARENTHESIS')),
        );

        $this->actions = array('insertEdge'   => array(0 => array('Arguments' => 'ARGUMENT')));
        $this->checkAuto();

        return $this->checkRemaining();
    }
}
?>