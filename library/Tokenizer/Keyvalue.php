<?php

namespace Tokenizer;

class Keyvalue extends TokenAuto {
    static public $operators = array('T_DOUBLE_ARROW');

    function _check() {
        $operands = array('Variable', 'Reference', 'Property', 'Staticproperty', 'Array');
        $this->conditions = array(-2 => array('filterOut' => array_merge(array( 'T_NS_SEPARATOR', 'T_DOT', ),
                                                                         Addition::$operators, Multiplication::$operators, Comparison::$operators)), 
                                  -1 => array('atom' => array('Variable', 'Property', 'Staticproperty', 'Array')),
                                   0 => array('token' => Keyvalue::$operators),
                                   1 => array('atom' => $operands),
                                   2 => array('filterOut' => array_merge( Assignation::$operators, Addition::$operators, Multiplication::$operators, Comparison::$operators, 
                                            array('T_OPEN_BRACKET', 'T_OBJECT_OPERATOR', 'T_INC', 'T_DEC', 'T_NS_SEPARATOR',
                                                  'T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_DOT', 'T_DOUBLE_COLON', ))));
        
        $this->actions = array('transform'    => array(-1 => 'KEY',
                                                        1 => 'VALUE'),
                               'atom'       => 'Keyvalue');
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>
