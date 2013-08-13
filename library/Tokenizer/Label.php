<?php

namespace Tokenizer;

class Label extends TokenAuto {
    static public $operators = array('T_COLON');
    
    function _check() {
        $this->conditions = array(-2 => array('filterOut' => array_merge(array('T_QUESTION','T_CASE', 'T_DOT', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON'),
                                                                         Assignation::$operators, Addition::$operators, Multiplication::$operators,
                                                                         Comparison::$operators)), 
                                  -1 => array('atom' => 'String'),
                                   0 => array('token' => 'T_COLON'));
        
        $this->actions = array('transform'  => array(-1 => 'LABEL'),
                               'atom'       => 'Label',
                               'cleanIndex' => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>