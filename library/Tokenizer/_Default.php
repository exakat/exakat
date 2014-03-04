<?php

namespace Tokenizer;

class _Default extends TokenAuto {
    static public $operators = array('T_DEFAULT');

    public function _check() {
        $final_token = array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT', 'T_SEQUENCE_CASEDEFAULT', 'T_ENDSWITCH');
        
     // default : with nothing 
        $this->conditions = array(0 => array('token' => _Default::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  2 => array('token' => $final_token),
        );
        
        $this->actions = array('createVoidForDefault' => true,
                               'keepIndexed'       => true);
        $this->checkAuto();

        // default : ; // rest of the code
        $this->conditions = array(0 => array('token' => _Default::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  2 => array('token' => 'T_SEMICOLON', 'atom' => 'none'),
                                  3 => array('token' => $final_token),
        );
        
        $this->actions = array('createVoidForDefault' => true,
                               'keepIndexed'       => true);
        $this->checkAuto();

        // Case has only one instruction (case 'a': $x++;)
        $this->conditions = array( 0 => array('token' => _Default::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   2 => array('atom'  => 'yes', 'notAtom' => 'Block' ), 
                                   3 => array('token' => 'T_SEMICOLON', 'atom' => 'none'),
                                   4 => array('token' => $final_token));
        
        $this->actions = array('createBlockWithSequenceForDefault' => true,
                               'keepIndexed'                       => true);
        $this->checkAuto();

        // Case has only one instruction no semi-colon (case 'a': $x++;)
        $this->conditions = array( 0 => array('token' => _Default::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                   2 => array('atom'  => 'yes', 'notAtom' => 'Block'), 
                                   3 => array('token' => $final_token));
        
        $this->actions = array('createBlockWithSequenceForDefault' => true,
                               'keepIndexed'                       => true);
        $this->checkAuto();

   // create block for Default  default : $x++ (or a sequence).
        $this->conditions = array(  0 => array('token' => _Default::$operators,
                                               'atom' => 'none'),
                                    1 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                               'atom' => 'none'),
                                    2 => array('atom' => 'yes', 'notAtom' => array('Case', 'Default', 'SequenceCaseDefault', 'Block')),
                                    3 => array('token' => $final_token),
        );
        
        $this->actions = array('createBlockWithSequenceForDefault' => true,
                               'keepIndexed'                       => true);
        $this->checkAuto(); 

        // Default with block
        $this->conditions = array(0 => array('token' => _Default::$operators,
                                              'atom' => 'none'),
                                  1 => array('token' => array('T_COLON', 'T_SEMICOLON')),
                                  2 => array('atom' => array('Block')), 
                                  3 => array('token' => $final_token),
        );
        
        $this->actions = array('transform'   => array( 1 => 'DROP',
                                                       2 => 'CODE',),
                                'atom'       => 'Default',
                                'cleanIndex' => true );
        $this->checkAuto();

        // @note instructions after a default, but not separated by ;
        $this->conditions = array( 0 => array('token' => 'T_DEFAULT', 
                                              'atom'  => 'none',),
                                   1 => array('token' => array('T_COLON', 'T_SEMICOLON'),
                                              'atom'  => 'none', ), 
                                   2 => array('atom'  => 'yes'), 
                                   3 => array('atom'  => 'yes'), 
                                   4 => array('filterOut2' => array_merge(array('T_ELSE', 'T_ELSEIF', 'T_OPEN_PARENTHESIS'),
                                                                        Assignation::$operators, Property::$operators, StaticProperty::$operators,
                                                                        _Array::$operators, Bitshift::$operators, Comparison::$operators, Logical::$operators)),
        );
        
        $this->actions = array('createSequenceForDefaultWithoutSemicolon' => true,
                               'keepIndexed'                              => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = "default : " + it.out("CODE").next().fullcode; ';
    }

}

?>