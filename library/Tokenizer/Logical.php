<?php

namespace Tokenizer;

class Logical extends TokenAuto {
    static public $operators = array('T_AND', 'T_LOGICAL_AND', 'T_BOOLEAN_AND', 'T_ANDAND',
                                     'T_OR' , 'T_LOGICAL_OR' , 'T_BOOLEAN_OR', 'T_OROR',
                                     'T_XOR', 'T_LOGICAL_XOR', 'T_BOOLEAN_XOR');

    function _check() {
        $this->conditions = array( -2 => array('filterOut' => array_merge(array('T_BANG', 'T_AT', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', ), 
                                                                          Comparison::$operators, Bitshift::$operators, Addition::$operators,
                                                                          Multiplication::$operators)),
                                   -1 => array('atom' => 'yes', 'notAtom' => 'Sequence' ), 
                                    0 => array('token' => Logical::$operators,
                                               'atom' => 'none'),
                                    1 => array('atom' => 'yes'),
                                    2 => array('filterOut' => array_merge(Comparison::$operators, Assignation::$operators, Addition::$operators, 
                                                                          Multiplication::$operators, Bitshift::$operators, 
                                                                           array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET', 
                                                                                 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_INC', 'T_DEC',
                                                                           ))));
        
        $this->actions = array('transform'  => array( -1 => 'LEFT',
                                                       1 => 'RIGHT'),
                               'atom'       => 'Logical',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}
?>