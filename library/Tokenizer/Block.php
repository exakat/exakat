<?php

namespace Tokenizer;

class Block extends TokenAuto {
    static public $operators = array('T_OPEN_CURLY');

    function _check() {
    // @doc Block
        $this->conditions = array( -1 => array('filterOut2' => array('T_VARIABLE', 'T_DOLLAR', 'T_CLOSE_CURLY', 'T_OPEN_BRACKET', 'T_CLOSE_BRACKET', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON' )),
                                    0 => array('token' => Block::$operators),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => 'T_CLOSE_CURLY',
                                               'atom' => 'none'),
        );
        
        $this->actions = array('transform'  => array(1 => 'CODE',
                                                     2 => 'DROP'),
                               'atom'       => 'Block',
                               'cleanIndex' => true
                               );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>