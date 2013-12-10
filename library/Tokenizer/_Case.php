<?php

namespace Tokenizer;

class _Case extends TokenAuto {
    static public $operators = array('T_CASE');
    
    function _check() {
        $final_token = array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT', 'T_ENDSWITCH');

        // @todo move to load
        // Case is empty (case 'a': )
        $this->conditions = array(0 => array('token' => _Case::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  3 => array('token' => $final_token),
        );
        
        $this->actions = array('createVoidForCase' => true,
                               'keepIndexed'       => true);
        $this->checkAuto();

        // Case is empty (case 'a':; )
        // @todo move to load
        $this->conditions = array(0 => array('token' => _Case::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  3 => array('token' => array('T_COLON', 'T_SEMICOLON'), 'atom' => 'none'),
                                  4 => array('token' => $final_token),
        );
        
        $this->actions = array('createVoidForCase' => true,
                               'keepIndexed'       => true);
        $this->checkAuto();

        // Case has only one instruction empty (case 'a': $x++)
        $this->conditions = array( 0 => array('token' => _Case::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   3 => array('atom'  => 'yes', 'notAtom' => 'Block'), 
                                   4 => array('token' => 'T_SEMICOLON', 'atom' => 'none'),
                                   5 => array('token' => $final_token));
        
        $this->actions = array('createBlockWithSequenceForCase'    => true,
                               'keepIndexed'                       => true);
        $r = $this->checkAuto();

        // Case has only one instruction and no ; (case 'a': $x++)
        $this->conditions = array( 0 => array('token' => _Case::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   3 => array('atom'  => 'yes', 'notAtom' => 'Block'), 
                                   4 => array('token' => $final_token));
        
        $this->actions = array('createBlockWithSequenceForCase'    => true,
                               'keepIndexed'                       => true);
        $r = $this->checkAuto();

    // create block for Case  case 'a' : $x++; (or a sequence).
        $this->conditions = array(  0 => array('token' => _Case::$operators,
                                               'atom' => 'none'),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                               'atom' => 'none'),
                                    3 => array('atom' => 'yes', 'notAtom' => array('Case', 'Default', 'SequenceCaseDefault', 'Block', )),
                                    4 => array('token' => $final_token,
                                                'atom' => 'yes'),
                                    //, 'T_SEMICOLON'
        );
        
        $this->actions = array('createBlockWithSequenceForCase'    => true,
                               'keepIndexed'                       => true);
        $this->checkAuto(); 

        // Case is followed by a block
        $this->conditions = array(0 => array('token' => _Case::$operators,
                                              'atom' => 'none'),
                                  1 => array('atom'  => 'yes'),
                                  2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  3 => array('atom'  => array('Block')), 
                                  4 => array('token' => $final_token),
        );
        
        $this->actions = array('transform'   => array( 1 => 'CASE',
                                                       2 => 'DROP',
                                                       3 => 'CODE',),
                                'atom'       => 'Case',
                                'cleanIndex' => true );
        $this->checkAuto();

        // @note instructions after a case, but not separated by ;
        $this->conditions = array( 0 => array('token' => 'T_CASE', 
                                              'atom'  => 'none',),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                              'atom'  => 'none', ), 
                                   3 => array('atom'  => 'yes'), //array('Ifthen', 'Sequence', 'Block', 'Switch', 'Return', 'For', 'Foreach',  'String', 'RawString')),
                                   4 => array('atom'  => 'yes'), //array('Ifthen', 'Sequence', 'Break', 'Block', 'Switch', 'Return', 'For', 'Foreach', 'String', 'RawString')),
                                   5 => array('filterOut2' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('createSequenceForCaseWithoutSemicolon' => true,
                               'keepIndexed'                           => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }

    function fullcode() {
        return 'it.fullcode = "case " + it.out("CASE").next().fullcode + " : " + it.out("CODE").next().fullcode; ';
    }
}

?>