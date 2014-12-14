<?php

namespace Tokenizer;

class Block extends TokenAuto {
    static public $operators = array('T_OPEN_CURLY');
    static public $atom = 'Sequence';
    
    public function _check() {
    // @doc {{ Block}}
        $this->conditions = array( -1 => array('token'   => array('T_OPEN_CURLY')), 
                                    0 => array('token'   => self::$operators),
                                    1 => array('atom'    => 'yes',
                                               'notAtom' => 'SequenceCaseDefault'),
                                    2 => array('token'   => 'T_CLOSE_CURLY',
                                               'atom'    => 'none'),
                                    3 => array('token'   => array('T_CLOSE_CURLY', 'T_SEMICOLON')), 
        );
        
        $this->actions = array('to_block'     => true,
                               'atom'         => 'Sequence',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               'property'     => array('bracket' => 'true')
                               );
        $this->checkAuto(); 

    // @doc Block
        $this->conditions = array( -1 => array('filterOut2' => array('T_VARIABLE', 'T_DOLLAR', 
                                                                     'T_CLOSE_CURLY', 'T_OPEN_CURLY',// $x{1}{3}, 
                                                                     'T_OPEN_BRACKET', 'T_CLOSE_BRACKET',  // $x[1]{3}, 
                                                                     'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_AT',
                                                                     'T_STRING')), 
                                    0 => array('token'      => self::$operators),
                                    1 => array('atom'       => 'yes',
                                               'notAtom'    => 'SequenceCaseDefault'),
                                    2 => array('token'      => 'T_CLOSE_CURLY',
                                               'atom'       => 'none'),
        );
        
        $this->actions = array('to_block'     => true,
                               'atom'         => 'Sequence',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               'property'     => array('bracket' => 'true')
                               );
        $this->checkAuto(); 

   // @doc interface xxxx { /**/ }
        $this->conditions = array( -2 => array('token'      => array('T_EXTENDS', 'T_IMPLEMENTS', 'T_INTERFACE', 'T_CLASS',
                                                                     'T_NAMESPACE', 'T_TRAIT', 'T_USE')), 
                                   -1 => array('token'      => 'T_STRING'), 
                                    0 => array('token'      => self::$operators),
                                    1 => array('atom'       => 'yes',
                                               'notAtom'    => 'SequenceCaseDefault'),
                                    2 => array('token'      => 'T_CLOSE_CURLY',
                                               'atom'       => 'none'),
        );
        
        $this->actions = array('to_block'     => true,
                               'atom'         => 'Sequence',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               'property'     => array('bracket' => 'true')
                               );
        $this->checkAuto(); 

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (fullcode.getProperty('bracket')) {
    fullcode.setProperty('fullcode', "{ /**/ } "); 
} else {
    fullcode.setProperty('fullcode', " /**/ "); 
}

GREMLIN;
    }
}

?>