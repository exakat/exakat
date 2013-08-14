<?php

namespace Tokenizer;

class IfthenElse extends TokenAuto {
    public static $operators = array('T_ELSE');
    
    function _check() {
        $this->conditions = array(  0 => array('token'     => IfthenElse::$operators,
                                               'atom'      => 'none'),
                                    1 => array('notAtom'   => 'Block', 'atom' => 'yes'),
                                    2 => array('filterOut' => array_merge(Assignation::$operators, Multiplication::$operators, 
                                                                          array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_OPEN_BRACKET', 'T_OPEN_CURLY'))),
        );
        
        $this->actions = array( 'to_block_else' => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>