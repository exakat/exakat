<?php

namespace Tokenizer;

class Addition extends TokenAuto {
    static public $operators = array('T_PLUS','T_MINUS');
    
    function _check() {
        // note : Multiplication:: and Addition:: operators are the same! 
        $this->conditions = array(-2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_DOT')),
                                  -1 => array('atom' => Multiplication::$operands ),
                                   0 => array('token' => Addition::$operators,
                                             'atom' => 'none'),
                                   1 => array('atom' => Multiplication::$operands),
                                   2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET')),
        );
        
        $this->actions = array('makeEdge'    => array('1' => 'RIGHT',
                                                      '-1' => 'LEFT'
                                                      ),
                               'atom'       => 'Addition',
                               );
        return $this->checkAuto();
    }
}
?>