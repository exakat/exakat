<?php

namespace Tokenizer;

class _Foreach extends TokenAuto {
    static public $operators = array('T_FOREACH');

    public function _check() {
        $operands = array('Variable', 'Array', 'Property', 'Staticproperty', 'Functioncall', 
                          'Staticmethodcall', 'Methodcall','Cast', 'Parenthesis', 'Ternary', 
                          'Noscream', 'Not', 'Assignation', 'New', 'Addition', 'Clone');
        $blind_variables = array('Variable', 'Keyvalue', 'Array', 'Staticproperty', 'Property' );

        // foreach(); (empty)
        $this->conditions = array( 0 => array('token' => _Foreach::$operators,
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom' => $operands), 
                                   3 => array('token' => 'T_AS'),
                                   4 => array('atom' => $blind_variables),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('token' => 'T_SEMICOLON')
        );
        $this->actions = array('addEdge'      => array(6 => array('Void' => 'LEVEL')),
                               'keepIndexed'  => true,
                               'cleanIndex'   => true);
        $this->checkAuto();

        // foreach() $x++; (one instruction)
        $this->conditions = array( 0 => array('token'   => _Foreach::$operators,
                                              'atom'    => 'none'),
                                   1 => array('token'   => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'    => $operands), 
                                   3 => array('token'   => 'T_AS'),
                                   4 => array('atom'    => $blind_variables),
                                   5 => array('token'   => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('atom'    => 'yes', 
                                              'notAtom' => 'Sequence'),
                                   7 => array('filterOut' => array_merge(array('T_OPEN_BRACKET', 'T_OBJECT_OPERATOR', 
                                                                               'T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS'), 
                                                                Assignation::$operators, Addition::$operators, 
                                                                Multiplication::$operators)),
        );
        $this->actions = array( 'to_block_foreach' => true,
                                'keepIndexed'      => true,
                                'cleanIndex'       => true);
        $this->checkAuto();

    // @doc foreach($x as $y) { code }
        $this->conditions = array( 0 => array('token' => _Foreach::$operators,
                                              'atom'  => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'  => $operands), 
                                   3 => array('token' => 'T_AS'),
                                   4 => array('atom'  => array('Variable', 'Keyvalue', 'Array', 'Staticproperty', 'Property' )),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('atom'  => 'Sequence'),
        );
        
        $this->actions = array('transform'    => array('1' => 'DROP',
                                                       '2' => 'SOURCE',    
                                                       '3' => 'DROP',
                                                       '4' => 'VALUE',
                                                       '5' => 'DROP',
                                                       '6' => 'LOOP',
                                                      ),
                               'atom'         => 'Foreach',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto(); 

    // @doc foreach($a as $b) : code endforeach
        $this->conditions = array( 0  => array('token' => _Foreach::$operators,
                                               'atom' => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom' => $operands), 
                                   3 => array('token' => 'T_AS'),
                                   4 => array('atom'  => array('Variable', 'Keyvalue', 'Array', 'Staticproperty', 'Property' )),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('token' => 'T_COLON'),
                                   7 => array('atom'  => 'yes'),
                                   8 => array('token' => 'T_ENDFOREACH'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'SOURCE',    
                                                        3 => 'DROP',
                                                        4 => 'VALUE',
                                                        5 => 'DROP',
                                                        6 => 'DROP',
                                                        7 => 'LOOP',
                                                        8 => 'DROP',
                                                      ),
                               'atom'         => 'Foreach',
                               'property'     => array('Alternative' => 'yes'),
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto(); 

    // @doc foreach($a as $b) : code ; endforeach
        $this->conditions = array( 0  => array('token' => _Foreach::$operators,
                                               'atom'  => 'none'),
                                   1 => array('token'  => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'   => $operands), 
                                   3 => array('token'  => 'T_AS'),
                                   4 => array('atom'   => array('Variable', 'Keyvalue', 'Array', 'Staticproperty', 'Property' )),
                                   5 => array('token'  => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('token'  => 'T_COLON'),
                                   7 => array('atom'   => 'yes'),
                                   8 => array('token'  => 'T_SEMICOLON',
                                              'atom'   => 'none'),
                                   9 => array('token'  => 'T_ENDFOREACH'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'SOURCE',    
                                                        3 => 'DROP',
                                                        4 => 'VALUE',
                                                        5 => 'DROP',
                                                        6 => 'DROP',
                                                        7 => 'LOOP',
                                                        8 => 'DROP',
                                                        9 => 'DROP',
                                                      ),
                               'atom'         => 'Foreach',
                               'property'     => array('Alternative' => 'yes'),
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

it.fullcode = "foreach(" + it.out("SOURCE").next().fullcode + " as " + it.out("VALUE").next().fullcode + ")" + it.out("LOOP").next().fullcode;

GREMLIN;
    }
}

?>