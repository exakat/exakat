<?php

namespace Tokenizer;

class Block extends TokenAuto {
    static public $operators = array('T_OPEN_CURLY');

    public function _check() {
    // @doc Block
    //'T_OPEN_CURLY' + atom not null
        $this->conditions = array( -1 => array('filterOut2' => array('T_VARIABLE', 'T_DOLLAR', 'T_CLOSE_CURLY', 'T_OPEN_CURLY',
                                                                     'T_OPEN_BRACKET', 'T_CLOSE_BRACKET', 
                                                                     'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON' )),
                                    0 => array('token'      => Block::$operators),
                                    1 => array('atom'       => 'yes'),
                                    2 => array('token'      => 'T_CLOSE_CURLY',
                                               'atom'       => 'none'),
        );
        
        $this->actions = array('transform'    => array(1 => 'CODE',
                                                       2 => 'DROP'),
                               'atom'         => 'Sequence',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               );
        $this->checkAuto(); 
        

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "{ /**/ } "; 
GREMLIN;
    }
}

?>