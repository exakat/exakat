<?php

namespace Tokenizer;

class _Default extends TokenAuto {
    static public $operators = array('T_DEFAULT');

    function _check() {
     // default : with nothing 
        $this->conditions = array(0 => array('token' => _Default::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  2 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT')),
        );
        
        $this->actions = array('createVoidForDefault' => true,
                               'keepIndexed'       => true);
        $this->checkAuto();

        // Case has only one instruction empty (case 'a': $x++;)
        $this->conditions = array( 0 => array('token' => _Default::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   2 => array('atom'  => 'yes'), 
                                   3 => array('token' => 'T_SEMICOLON'),
                                   4 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT')));
        
        $this->actions = array('createBlockWithSequenceForDefault' => true,
                               'keepIndexed'                       => true);
        $this->checkAuto();

/*
        $this->conditions = array(-2 => array('token' => _Default::$operators,
                                              'atom' => 'none'),
                                  -1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   0 => array('atom' => 'Sequence'), 
                                   1 => array('atom' => 'Sequence'), 
        );
        $this->actions = array( 'transform' => array(1 => 'ELEMENT'), 
                                'mergeNext' => array('Sequence' => 'ELEMENT'));
        $this->checkAuto();
*/
   // create block for Default  default : $x++ (or a sequence).
        $this->conditions = array(  0 => array('token' => _Default::$operators,
                                               'atom' => 'none'),
                                    1 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                               'atom' => 'none'),
                                    2 => array('atom' => 'yes', 'notAtom' => array('Case', 'Default', 'SequenceCaseDefault')),
                                    3 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT')),
        );
        
        $this->actions = array('createBlockWithSequenceForDefault' => true,
                               'keepIndexed'                       => true);
        $this->checkAuto(); 

        // Default with block
        $this->conditions = array(0 => array('token' => _Default::$operators,
                                              'atom' => 'none'),
                                  1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  2 => array('atom' => array('Block')), 
        );
        
        $this->actions = array('transform'   => array( 1 => 'DROP',
                                                       2 => 'CODE',),
                                'atom'       => 'Default',
                                'cleanIndex' => true );
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>