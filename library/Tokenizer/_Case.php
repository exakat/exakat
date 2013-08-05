<?php

namespace Tokenizer;

class _Case extends TokenAuto {
    static public $operators = array('T_CASE');
    
    function _check() {
        // Case is empty (case 'a': )
        $this->conditions = array(0 => array('token' => _Case::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  3 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT')),
        );
        
        $this->actions = array('createVoidForCase' => true,
                               'keepIndexed'       => true);
        $this->checkAuto();

        // Case has only one instruction empty (case 'a': $x++)
        $this->conditions = array( 0 => array('token' => _Case::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   3 => array('atom'  => 'yes'), 
                                   4 => array('token' => 'T_SEMICOLON'),
                                   5 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT')));
        
        $this->actions = array('createBlockWithSequenceForCase'    => true,
                               'keepIndexed'                       => true);
        $r = $this->checkAuto();

    // create block for Case  case 'a' : $x++; (or a sequence).
        $this->conditions = array(  0 => array('token' => _Case::$operators,
                                               'atom' => 'none'),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                               'atom' => 'none'),
                                    3 => array('atom' => 'yes', 'notAtom' => array('Case', 'Default', 'SequenceCaseDefault')),
                                    4 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT')),
                                    //, 'T_SEMICOLON'
        );
        
        $this->actions = array('createBlockWithSequenceForCase'    => true,
                               'keepIndexed'                       => true);
        $this->checkAuto(); 

        // Case is followed by a block
        $this->conditions = array(0 => array('token' => _Case::$operators,
                                              'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  3 => array('atom' => array('Block')), 
        );
        
        $this->actions = array('transform'   => array( 1 => 'CASE',
                                                       2 => 'DROP',
                                                       3 => 'CODE',),
                                'atom'       => 'Case',
                                'cleanIndex' => true );
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>