<?php

namespace Tokenizer;

class Blockspecial extends TokenAuto {

    function _check() {
        $this->conditions = array( -1 => array('token'     => 'T_ELSE',
                                               'atom'      => 'none'),
                                    0 => array('notAtom'   => 'Block', 'atom' => 'yes', ),
                                    1 => array('filterOut' => array_merge(Assignation::$operators, Multiplication::$operators, 
                                                                          array('T_OPEN_PARENTHESIS'))),
        );
        
        $this->actions = array( 'to_block' => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>