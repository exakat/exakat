<?php

namespace Tokenizer;

class Block extends TokenAuto {
    static public $operators = array('T_OPEN_CURLY');
    static public $atom = 'Sequence';
    
    public function _check() {
    // @doc Block
        $this->conditions = array( -1 => array('filterOut2' => array('T_VARIABLE', 'T_DOLLAR', 'T_CLOSE_CURLY', 'T_OPEN_CURLY',
                                                                     'T_OPEN_BRACKET', 'T_CLOSE_BRACKET',  // $x[1]{3}, 
                                                                     'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_AT', 'T_CLOSE_PARENTHESIS' )),
                                    0 => array('token'      => Block::$operators),
                                    1 => array('atom'       => 'yes',
                                               'notAtom'    => 'SequenceCaseDefault'),
                                    2 => array('token'      => 'T_CLOSE_CURLY',
                                               'atom'       => 'none'),
        );
        
        $this->actions = array('transform'    => array(1 => 'ELEMENT',
                                                       2 => 'DROP'),
                               'atom'         => 'Sequence',
                               'cleanIndex'   => true,
                               'property'     => array('block' => 'true'),
                               'makeSequence' => 'it',
                               );
//        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', "{ /**/ } "); 
GREMLIN;
    }
}

?>