<?php

namespace Tokenizer;

class Ifthen extends TokenAuto {
    static public $operators = array('T_IF', 'T_ELSEIF');
    static public $atom = 'Ifthen';

    public function _check() {
    
    // @doc if () with only ;
        $this->conditions = array( 0 => array('token' => Ifthen::$operators),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_SEMICOLON', 
                                              'atom'  => 'none')
        );
        
        $this->actions = array('addEdge'     => array(2 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'property'    => array('alternative' => 'false'),
                               'cleanIndex'  => true);
        $this->checkAuto(); 

        // @doc if () $x++;
        // Make a block from sequence after a if/elseif
        $this->conditions = array(  0 => array('token'     => Ifthen::$operators,
                                               'atom'      => 'none'),
                                    1 => array('atom'      => 'Parenthesis'),
                                    2 => array('notAtom'   => 'Sequence', 
                                               'atom'      => 'yes'),
                                    3 => array('token'     => array('T_SEMICOLON', 'T_ELSEIF', 'T_ELSE', 'T_IF',
                                                                    'T_ENDIF', 'T_CLOSE_TAG', 'T_INLINE_HTML', 
                                                                    'T_CLOSE_CURLY')),
        );
        
        $this->actions = array( 'to_block_ifelseif' => 2,
                                'keepIndexed'       => true);
        $this->checkAuto(); 

    // @doc if then else
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('atom'  =>  'Sequence',
                                              'property' => array('block' => 'true')),
                                   3 => array('token' => 'T_ELSE', 
                                              'atom'  => 'none'),
                                   4 => array('atom'  => 'Sequence',
                                              'property' => array('block' => 'true')),
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'THEN',    
                                                       3 => 'DROP',
                                                       4 => 'ELSE'),
                               'atom'         => 'Ifthen',
                               'property'     => array('alternative' => 'false'),
                               'makeSequence' => 'it',
                               'cleanIndex'   => true);
        $this->checkAuto(); 

    // @doc if then without else
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => 'Sequence',
                                              'property' => array('block' => 'true')),
                                   3 => array('filterOut2' => array('T_ELSE', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'THEN'),
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => 'false'),
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true);
        $this->checkAuto(); 

    // @doc if then elseif without else
        $this->conditions = array( 0 => array('token' => Ifthen::$operators),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('atom'  => 'Sequence',
                                              'property' => array('block' => 'true')),
                                   3 => array('atom'  => 'Ifthen', 
                                              'token' => 'T_ELSEIF',
                                              'property' => array('alternative' => 'false'))
        );
        
        $this->actions = array('transform'    => array('1' => 'CONDITION',
                                                       '2' => 'THEN',    
                                                       '3' => 'ELSE'
                                                      ),
                               'property'     => array('alternative' => 'false'),
                               'makeSequence' => 'it',
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true
                               );
        $this->checkAuto(); 
        
    // @doc if () : endif (empty )
        $this->conditions = array( 0 => array('token' => Ifthen::$operators),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('token' => array('T_ENDIF', 'T_ELSEIF', 'T_ELSE')),
        );
        
        $this->actions = array('insertVoid'  => 2,
                               'keepIndexed' => true,
                               'property'    => array('alternative' => 'true'),
                               'cleanIndex'  => true);
        $this->checkAuto(); 

        // Make a block from sequence after a if/elseif (alternative syntax)
        $this->conditions = array(  0 => array('token'     => Ifthen::$operators,
                                               'atom'      => 'none'),
                                    1 => array('atom'      => 'Parenthesis'),
                                    2 => array('token'     => array('T_COLON', 'T_SEMICOLON')),
                                    3 => array('notAtom'   => array('Sequence', 'Void'), 
                                               'atom'      => 'yes'),
                                    4 => array('token'     => 'T_SEMICOLON', 
                                               'atom'      => 'none'),
                                    5 => array('token'     => array('T_ELSEIF', 'T_ENDIF', 'T_ELSE'))
        );
        
        $this->actions = array( 'to_block_ifelseif' => 3,
                                'keepIndexed'       => true);
        $this->checkAuto(); 

        // if, elseif followed by a single instruction without a ;
        $this->conditions = array(  0 => array('token' => Ifthen::$operators,
                                               'atom' => 'none'),
                                    1 => array('atom' => 'Parenthesis'),
                                    2 => array('atom' => array('For', 'Switch', 'Foreach', 'While', 'Dowhile', 'Ifthen', 'Assignation' ))
        );
        
        $this->actions = array( 'to_block_ifelseif_instruction' => true,
                                'property'                      => array('alternative' => 'false'),
                                'keepIndexed'                   => true);
        $this->checkAuto(); 

    // @doc if then NO ELSE (2)
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('atom'  => array('Sequence', 'Void')),
                                   3 => array('token' => array('T_ELSE', 'T_ELSEIF')),
                                   4 => array('token' => 'T_COLON'),
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'THEN'),
                               'property'     => array('alternative' => 'true'),
                               'makeSequence' => 'it',
                               'atom'         => 'Ifthen',
                               'cleanIndex'   => true);
        $this->checkAuto(); 
        
    // @doc if then NO ELSE, with a sequence behind
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'Parenthesis'),
                                   2 => array('atom' => array('Sequence', 'Void')),
                                   3 => array('atom' => 'Sequence'),
                                   4 => array('filterOut2' => array('T_ELSE', 'T_ELSEIF'))
        );
        
        $this->actions = array('transform'    => array(1 => 'CONDITION',
                                                       2 => 'THEN'),
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => 'false'),
                               'atom'         => 'Ifthen');
        $this->checkAuto(); 

    // @doc if ( ) : endif
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('token' => 'T_ENDIF'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',    
                                                        3 => 'THEN',    
                                                        4 => 'DROP'),
                               'property'     => array('alternative' => 'true'),
                               'atom'         => 'Ifthen',
                               'makeSequence' => 'it',
                               'cleanIndex'   => true
                               );
        $this->checkAuto(); 

    // @doc if ( ) : else: endif (alternative syntax)
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('token' => 'T_ELSE'),
                                   5 => array('token' => 'T_COLON'),
                                   6 => array('atom'  => 'yes',),
                                   7 => array('token' => array('T_ENDIF', 'T_ELSEIF')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',    
                                                        3 => 'THEN',    
                                                        4 => 'DROP', 
                                                        5 => 'DROP', 
                                                        6 => 'ELSE', 
                                                        7 => 'DROP', 
                                                      ),
                               'atom'         => 'Ifthen',
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => 'true'),
                               'cleanIndex'   => true
                               );

        $this->checkAuto(); 

    // @doc if ( ) : else  (partial alternative syntax)
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => 'yes',),
                                   4 => array('token' => 'T_ELSE'),
                                   5 => array('atom'  => 'Sequence'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',    
                                                        3 => 'THEN',    
                                                        4 => 'DROP', 
                                                        5 => 'ELSE' 
                                                      ),
                               'atom'         => 'Ifthen',
                               'makeSequence' => 'it',
                               'property'     => array('alternative' => 'true'),
                               'cleanIndex'   => true
                               );

        $this->checkAuto(); 

    // @doc if ( ) : elseif
        $this->conditions = array( 0 => array('token' => Ifthen::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => 'Parenthesis'),
                                   2 => array('token' => 'T_COLON'),
                                   3 => array('atom'  => 'yes'),
                                   4 => array('atom'  => 'Ifthen', 
                                              'token' => 'T_ELSEIF',
                                              'property' => array('alternative' => 'true') ),
                                   5 => array('filterOut2' => 'T_ENDIF'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'CONDITION',
                                                        2 => 'DROP',    
                                                        3 => 'THEN', 
                                                        4 => 'ELSE',
                                                      ),
                               'atom'         => 'Ifthen',
                               'makeSequence' => 'it',
                               'cleanIndex'   => true,
                               'property'     => array('alternative' => 'true')
                               );
        $this->checkAuto();

        // @note instructions after a if, but not separated by ;
        $this->conditions = array( 0 => array('token'      => 'T_IF', 
                                              'atom'       => 'none',),
                                   1 => array('atom'       => 'Parenthesis'),
                                   2 => array('token'      => 'T_COLON',
                                              'atom'       => 'none', ), 
                                   3 => array('atom'       => 'yes', 
                                              'notAtom'    => 'Sequence'), 
                                   4 => array('atom'       => 'yes', 
                                              'notAtom'    => 'Sequence'), 
                                   5 => array('filterOut2' => array_merge(array('T_OPEN_PARENTHESIS'),
                                                                        Assignation::$operators,    Property::$operators, 
                                                                        StaticProperty::$operators, _Array::$operators, 
                                                                        Bitshift::$operators,       Comparison::$operators, 
                                                                        Logical::$operators)),
        );
        
        $this->actions = array('createSequenceForCaseWithoutSemicolon' => true,
                               'property'                              => array('alternative' => 'true'),
                               'keepIndexed'                           => true);
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "if " + fullcode.out("CONDITION").next().fullcode + " " + fullcode.out("THEN").next().fullcode;

GREMLIN;
    }

}

?>