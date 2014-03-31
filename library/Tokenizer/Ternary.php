<?php

namespace Tokenizer;

class Ternary extends TokenAuto {
    static public $operators = array('T_QUESTION');
    
    public function _check() {
        
        // $a ? $b : $c
        $this->conditions = array( -2 => array('filterOut' => array_merge(array('T_BANG', 'T_AT', 'T_DOUBLE_COLON', 
                                                                                'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON' ), 
                                                                            Comparison::$operators, Logical::$operators, 
                                                                            Bitshift::$operators, Multiplication::$operators, 
                                                                            Addition::$operators, Concatenation::$operators)),
                                   -1 => array('atom' => 'yes'),
                                    0 => array('token' => Ternary::$operators),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => 'T_COLON'),
                                    3 => array('atom' => 'yes', 'notAtom' => 'Sequence'),
                                    4 => array('filterOut' => Token::$instruction_ending),
                                 );
        
        $this->actions = array('transform'    => array( -1 => 'CONDITION',
                                                         1 => 'THEN',
                                                         2 => 'DROP',
                                                         3 => 'ELSE',
                                                        ),
                               'atom'         => 'Ternary',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto(); 

        // $a ?: $b : we keep the : as 'Then', and it will have to be interpreted as $a later. May need to build a specific processing here.
        $this->conditions = array( -2 => array('filterOut' => array_merge(array('T_BANG', 'T_AT', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 
                                                                                'T_DOUBLE_COLON' ), 
                                                                            Comparison::$operators, Logical::$operators, 
                                                                            Bitshift::$operators)),
                                   -1 => array('atom' => 'yes'),
                                    0 => array('token' => Ternary::$operators),
                                    1 => array('token' => 'T_COLON'),
                                    2 => array('atom' => 'yes', 
                                               'notAtom' => 'Sequence'),
                                    3 => array('filterOut' => array('T_DOT', 'T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 
                                                                    'T_OPEN_BRACKET', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                 );
        
        $this->actions = array('transform'    => array( -1 => 'CONDITION',
                                                         1 => 'THEN',
                                                         2 => 'ELSE'
                                                       ),
                               'atom'         => 'Ternary',
                               'atom1'        => 'TernaryElse',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN
it.fullcode = it.out("CONDITION").next().fullcode + " ? " + it.out("THEN").next().fullcode + " : " + it.out("ELSE").next().fullcode; 

GREMLIN;
    }
}

?>