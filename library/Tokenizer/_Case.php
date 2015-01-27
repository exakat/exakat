<?php

namespace Tokenizer;

class _Case extends TokenAuto {
    static public $operators = array('T_CASE');
    static public $atom = 'Case';
    
    public function _check() {
        $finalToken = array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT', 'T_ENDSWITCH');

        // @todo move to load
        // Case is empty (case 'a': )
        $this->conditions = array(0 => array('token' => _Case::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  3 => array('token' => $finalToken),
        );
        
        $this->actions = array('createVoidForCase' => true,
                               'keepIndexed'       => true);
        $this->checkAuto();

        // Case is empty (case 'a':; )
        // @todo move to load
        $this->conditions = array(0 => array('token' => _Case::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => 'yes'),
                                  2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  3 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                             'atom'  => 'none'),
//                                  4 => array('token' => $finalToken),
        );
        
        $this->actions = array('createVoidForCase' => true,
                               'keepIndexed'       => true);
        $this->checkAuto();

        // Case has only one instruction empty (case 'a': ;)
        $this->conditions = array( 0 => array('token' => _Case::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   3 => array('atom'  => 'yes',
                                              'notAtom' => 'Sequence'),
                                   4 => array('token' => 'T_SEMICOLON',
                                              'atom' => 'none'),
                                   5 => array('token' => $finalToken));
        
        $this->actions = array('createBlockWithSequenceForCase' => true,
                               'keepIndexed'                    => true);
        $this->checkAuto();

        // Case has only one instruction and no ; (case 'a': $x++)
        $this->conditions = array( 0 => array('token' => _Case::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   3 => array('atom'  => 'yes',
                                              'notAtom' => 'Sequence'),
                                   4 => array('token' => $finalToken));
        
        $this->actions = array('createBlockWithSequenceForCase' => true,
                               'keepIndexed'                    => true);
        $this->checkAuto();

    // create block for Case  case 'a' : $x++; (or a sequence).
        $this->conditions = array(  0 => array('token' => _Case::$operators,
                                               'atom' => 'none'),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                               'atom' => 'none'),
                                    3 => array('atom' => 'yes',
                                               'notAtom' => array('Case', 'Default', 'SequenceCaseDefault', 'Sequence')),
                                    4 => array('token' => 'T_SEMICOLON',
                                               'atom' => 'none'),
                                    5 => array('token' => $finalToken),
        );
        
        $this->actions = array('createBlockWithSequenceForCase'    => true,
                               'keepIndexed'                       => true);
        $this->checkAuto();

        // Case is followed by a block
        $this->conditions = array(0 => array('token' => _Case::$operators,
                                              'atom' => 'none'),
                                  1 => array('atom'  => 'yes'),
                                  2 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  3 => array('atom'  => array('Sequence', 'Void')),
                                  4 => array('token' => $finalToken),
        );
        
        $this->actions = array('transform'            => array( 1 => 'CASE',
                                                                2 => 'DROP',
                                                                3 => 'CODE',),
                                'atom'                => 'Case',
                                'cleanIndex'          => true ,
                                'caseDefaultSequence' => true);
        $this->checkAuto();

        // @note instructions after a case, but not separated by ;
        $this->conditions = array( 0 => array('token' => 'T_CASE',
                                              'atom'  => 'none',),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                              'atom'  => 'none', ),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('atom'  => 'yes'),
                                   5 => array('filterOut2' => array_merge(array('T_ELSE', 'T_ELSEIF', 'T_OPEN_PARENTHESIS'),
                                                                        Assignation::$operators, Property::$operators,
                                                                        _Array::$operators, Bitshift::$operators,
                                                                        Comparison::$operators, Logical::$operators,
                                                                        Staticproperty::$operators)),
        );
        
        $this->actions = array('createSequenceForCaseWithoutSemicolon' => true,
                               'keepIndexed'                           => true);
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
fullcode.fullcode = "case " + fullcode.out("CASE").next().fullcode + " : " + fullcode.out("CODE").next().fullcode;

GREMLIN;
    }
}

?>
