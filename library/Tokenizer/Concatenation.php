<?php

namespace Tokenizer;

class Concatenation extends TokenAuto {
    function _check() {
        $operands = array('String', 'Integer', 'Float', 'Not', 'Variable','Array', 'Concatenation', 'Sign', 'Array',
                          'Functioncall', 'Noscream', 'Staticproperty', 'Staticmethodcall', 'Staticconstant',
                          'Methodcall', 'Parenthesis', 'Magicconstant', 'Property', 'Multiplication', 'Addition', 
                          'Preplusplus', 'Postplusplus',);
        
        $this->conditions = array(-2 => array('filterOut' => array_merge(Addition::$operators, Multiplication::$operators,
                                                            array('T_AT', 'T_NOT', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_DOLLAR'))), 
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => 'T_DOT',
                                              'atom'  => 'none'),
                                   1 => array('atom'  => $operands),
                                   2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_OPEN_CURLY', 'T_OPEN_BRACKET')),
        ); 
        
        $this->actions = array('makeEdge'   => array( 1 => 'CONCAT',
                                                     -1 => 'CONCAT'
                                                      ),
                               'order'      => array( 1 => 2,
                                                     -1 => 1 ),
                               'mergeNext'  => array('Concatenation' => 'CONCAT'), 
                               'atom'       => 'Concatenation',
                               );
        
        $this->checkAuto();

// Fusion of 2 concatenations
        $this->conditions = array( 0 => array('atom' => array('String', 'Variable', 'Property', 'Array', 'Phpcode', 'Concatenation')),
                                   1 => array('atom' => array('String', 'Variable', 'Property', 'Array', 'Phpcode', 'Concatenation')),
        ); 
        $this->actions = array('mergeConcat' => "Concat");
        $this->checkAuto();


// Fusion of string and PHPcode
        $this->conditions = array( 0 => array('atom' => array('String',  'Concatenation', )),
                                   1 => array('atom' => array('String', 'Phpcode', 'Concatenation', )),
        ); 

        $this->actions = array('insertConcat' => "Concat",
                               'keepIndexed'  => true);
//        $r = $this->checkAuto();

        return $this->checkRemaining();
    }
}
?>