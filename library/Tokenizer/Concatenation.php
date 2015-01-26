<?php

namespace Tokenizer;

class Concatenation extends TokenAuto {
    public static $operators = array('T_DOT');
    static public $atom = 'Concatenation';
    
    public function _check() {
        $operands = array('String', 'Identifier', 'Integer', 'Float', 'Not', 'Variable', 'Array', 'Concatenation', 'Sign', 'Array',
                          'Functioncall', 'Noscream', 'Staticproperty', 'Staticmethodcall', 'Staticconstant', 'Staticclass',
                          'Methodcall', 'Parenthesis', 'Magicconstant', 'Property', 'Multiplication', 'Addition', 'Power',
                          'Preplusplus', 'Postplusplus', 'Cast', 'Assignation', 'Nsname', 'Boolean', 'Null' );
        
        $this->conditions = array(-2 => array('token' => array_merge( Assignation::$operators, Comparison::$operators,
                                                                      Logical::$operators, _Include::$operators,
                                                                      array('T_QUESTION', 'T_COLON', 'T_COMMA', 'T_OPEN_PARENTHESIS',
                                                                            'T_OPEN_CURLY', 'T_OPEN_BRACKET', 'T_ECHO', 'T_OPEN_TAG', 
                                                                            'T_SEMICOLON', 'T_RETURN'))),
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => 'T_DOT'),
                                   1 => array('atom'  => $operands,
                                              'check_for_concatenation' => $operands
                                              ),
                                   2 => array('token' => array('T_CLOSE_PARENTHESIS', 'T_COLON', 'T_SEMICOLON', 'T_CLOSE_TAG',
                                                               'T_CLOSE_CURLY', 'T_CLOSE_BRACKET', 'T_DOT', 'T_QUESTION')),
        );
        
        $this->actions = array('to_concatenation' => true,
                               'atom'             => 'Concatenation',
                               'makeSequence'     => 'it',
                               );
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
s = [];
fullcode.out("CONCAT").sort{it.rank}._().each{ s.add(it.fullcode); };
fullcode.setProperty('fullcode', "" + s.join(" . ") + "");
fullcode.setProperty('count', s.size());

GREMLIN;
    }

}
?>
