<?php

namespace Tokenizer;

class _Case extends TokenAuto {
    static public $operators = array('T_CASE');
    
    function _check() {
        /*
        // Case is empty (case 'a': )
        $this->conditions = array(-2 => array('token' => _Case::$operators,
                                             'atom' => 'none'),
                                  -1 => array('atom' => 'yes'),
                                  0 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  1 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT')),
        );
        
        $this->actions = array('addEdge'    => array(0 => array('Block' => 'CODE')));
        $r = $this->checkAuto();

        // Case has only one instruction empty (case 'a': $x++)
        $this->conditions = array(-2 => array('token' => _Case::$operators,
                                              'atom' => 'none'),
                                  -1 => array('atom' => 'yes'),
                                   0 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   1 => array('atom' => array('Postplusplus', 'Assignation', 'Break', 'Return', 'Ifthen', 'Ternary', 'Include',   )), 
        );
        
        $this->actions = array('createSequenceWithNext'    => true);
        $r = $this->checkAuto();

        // Case is followed by 2 sequences
        $this->conditions = array(-3 => array('token' => _Case::$operators,
                                              'atom' => 'none'),
                                  -2 => array('atom' => 'yes'),
                                  -1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   0 => array('atom' => 'Sequence'), 
                                   1 => array('atom' => 'Sequence'), 
        );
        $this->actions = array( 'transform' => array(1 => 'ELEMENT'), 
                                'mergeNext' => array('Sequence' => 'ELEMENT'));
        $r = $this->checkAuto();
    */
    // create block for Case  case 'a' : $x++; (or a sequence).
    
        $this->conditions = array(  0 => array('token' => _Case::$operators,
                                               'atom' => 'none'),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                               'atom' => 'none'),
                                    3 => array('atom' => 'yes', 'notAtom' => array('Case', 'Default', 'SequenceCaseDefault')),
                                    4 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT', 'T_SEMICOLON')),
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
        $r = $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>