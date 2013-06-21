<?php

namespace Tokenizer;

class Sequence extends TokenAuto {
    function _check() {
        $operands = array('Addition', 'Multiplication', 'Sequence', 'String', 'Integer', 
                          'Float', 'Not', 'Variable','Array','Concatenation', 'Sign',
                          'Functioncall', 'Constant', 'Parenthesis', 'Comparison', 'Assignation',
                          'Noscream', 'Staticproperty', 'Property', 'Ternary', 'New', 'Return',
                          'Instanceof', 'Magicconstant', 'Staticconstant', 'Methodcall', 'Logical',
                           );
        
        $yield_operator = array('T_ECHO', 'T_PRINT', 'T_DOT', 'T_AT', 'T_OBJECT_OPERATOR', 'T_BANG',
                                 'T_DOUBLE_COLON', 'T_COLON', 'T_EQUAL', 'T_NEW', 'T_INSTANCEOF', 'T_AND', 'T_QUOTE');
        
        // @note instructions separated by ; 
        $this->conditions = array(-2 => array('filterOut' => $yield_operator), 
                                  -1 => array('atom' => $operands ),
                                   0 => array('code' => ';',
                                             'atom' => 'none'),
                                   1 => array('atom' => $operands),
        );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ELEMENT',
                                                      -1 => 'ELEMENT'
                                                      ),
                               'order'    => array('1'  => '2',
                                                   '-1' => '1'
                                                      ),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               );
        $r = $this->checkAuto();

        // @note End of PHP script
        $this->conditions = array(-2 => array('filterOut' => $yield_operator), 
                                  -1 => array('atom' => $operands ),
                                   0 => array('code' => ';',
                                              'atom' => 'none'),
                                   1 => array('token' => array('T_CLOSE_TAG', 'T_CLOSE_CURLY', 'T_END'),
                                              'atom'  => 'none'),
        );
        
        $this->actions = array('makeEdge'    => array(-1 => 'ELEMENT'
                                                      ),
                               'order'    => array('-1' => '1'),
                               'atom'       => 'Sequence',
                               );

        $r = $this->checkAuto();
        
        return $r;
    }
}
?>