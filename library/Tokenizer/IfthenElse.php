<?php

namespace Tokenizer;

class IfthenElse extends TokenAuto {
    public static $operators = array('T_ELSE');
    
    function _check() {
        $this->conditions = array(  0 => array('token'     => IfthenElse::$operators,
                                               'atom'      => 'none'),
                                    1 => array('notAtom'   => 'Block', 'atom' => 'yes'),
                                    2 => array('filterOut' => Token::$instruction_ending),
        );
        
        $this->actions = array( 'to_block_else' => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>