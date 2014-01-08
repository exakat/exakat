<?php

namespace Tokenizer;

class Parenthesis extends TokenAuto {
    static public $operators = array('T_OPEN_PARENTHESIS');
    
    function _check() {
        $operands    = array('Addition', 'Multiplication', 'Sequence', 'String', 
                             'Integer', 'Float', 'Not', 'Variable','Array', 'Concatenation', 'Sign',
                             'Functioncall', 'Boolean', 'Comparison', 'Parenthesis', 'Constant', 'Array',
                             'Instanceof', 'Noscream', 'Magicconstant', 'Logical', 'Ternary',
                             'Assignation', 'Property', 'Staticproperty', 'Staticconstant',
                             'Methodcall', 'Staticmethodcall', 'Bitshift', 'Cast', 'Preplusplus', 'Postplusplus',
                             'Include', 'New', 'Identifier', 
                              );

        $this->conditions = array(-1 => array('filterOut2' => array_merge(Functioncall::$operators, _Include::$operators, 
                                                                    array('T_STRING', 'T_CATCH', 'T_EXIT', 'T_FOR', 'T_SWITCH', 
                                                                    'T_WHILE', 'T_ECHO', 'T_UNSET', 'T_EMPTY', 'T_PRINT', 
                                                                    'T_VARIABLE', 'T_ISSET', 'T_ARRAY', 'T_EVAL', 'T_LIST', 
                                                                    'T_CLONE', 'T_DECLARE', 'T_CLOSE_BRACKET', 'T_STATIC', 
                                                                    'T_USE', 'T_NS_SEPARATOR', 'T_CLOSE_CURLY', 'T_FUNCTION',
                                                                    'T_THROW', 'T_CONTINUE' )),
                                              'notAtom' => array('Array', 'Property')), 
                                   0 => array('token' => Parenthesis::$operators,
                                              'atom' => 'none' ),
                                   1 => array('atom' => $operands),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom' => 'none'),
        );
        
        $this->actions = array('makeEdge' => array( '1' => 'CODE'),
                               'dropNext' => array(1),
                               'atom'     => 'Parenthesis',
                               'cleanIndex' => true);
        $this->checkAuto();

// this applies to situations like print ($a * $b) + $c; where parenthesis actually belong to the following expression. 
        $this->conditions = array(-1 => array('token' => array_merge(array('T_ECHO', 'T_PRINT'), _Include::$operators)), 
                                   0 => array('token' => Parenthesis::$operators,
                                              'atom'  => 'none' ),
                                   1 => array('atom'  => $operands),
                                   2 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
                                   3 => array('token' => array_merge(Logical::$operators, Multiplication::$operators, 
                                                                     Addition::$operators, Bitshift::$operators,
                                                                     Concatenation::$operators)),
        );
        
        $this->actions = array('makeEdge' => array( '1' => 'CODE'),
                               'dropNext' => array(1),
                               'atom'     => 'Parenthesis',
                               'cleanIndex' => true);
        
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    function fullcode() {
        return 'it.fullcode = "(" + it.out("CODE").next().fullcode + ")"; ';
    }
}
?>