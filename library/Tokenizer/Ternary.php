<?php

namespace Tokenizer;

class Ternary extends TokenAuto {
    function _check() {
        
        $operands = array('Constant');
        $this->conditions = array( -2 => array('filterOut' => array_merge(array('T_BANG', 'T_AT', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON' ), 
                                                                            Comparison::$operators)),
                                   -1 => array('atom' => 'yes'),
                                    0 => array('token' => 'T_QUESTION'),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => 'T_COLON'),
                                    3 => array('atom' => 'yes'),
                                    4 => array('filterOut' => array('T_DOT', 'T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET',)),
                                 );
        
        $this->actions = array('transform'   => array( -1 => 'CONDITION',
                                                        1 => 'THEN',
                                                        2 => 'DROP',
                                                        3 => 'ELSE',
                                                       ),
                               'atom'       => 'Ternary',
                               );
        $r = $this->checkAuto(); 

        return $r;
    }
}

?>