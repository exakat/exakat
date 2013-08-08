<?php

namespace Tokenizer;

class Concatenation extends TokenAuto {
    public static $operators = array('T_DOT');
    
    function _check() {
        $operands = array('String', 'Integer', 'Float', 'Not', 'Variable','Array', 'Concatenation', 'Sign', 'Array',
                          'Functioncall', 'Noscream', 'Staticproperty', 'Staticmethodcall', 'Staticconstant',
                          'Methodcall', 'Parenthesis', 'Magicconstant', 'Property', 'Multiplication', 'Addition', 
                          'Preplusplus', 'Postplusplus', 'Cast',);
        
        $this->conditions = array(-2 => array('filterOut' => array_merge(Addition::$operators, Multiplication::$operators,
                                                            array('T_AT', 'T_NOT', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_DOLLAR'))), 
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => Concatenation::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => $operands),
                                   2 => array('filterOut' => array_merge(array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 
                                                                               'T_OPEN_CURLY', 'T_OPEN_BRACKET'),
                                                                    Assignation::$operators)),
        ); 
        
        $this->actions = array('makeEdge'   => array( 1 => 'CONCAT',
                                                     -1 => 'CONCAT'
                                                      ),
                               'order'      => array( 1 => 2,
                                                     -1 => 1 ),
                               'mergeNext'  => array('Concatenation' => 'CONCAT'), 
                               'atom'       => 'Concatenation',
                               'cleanIndex' => true
                               );
        
        $this->checkAuto();

// Fusion of 2 concatenations
        $this->conditions = array( -1 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_COMMA')), 
                                   0 => array('atom' => array('String', 'Variable', 'Property', 'Array', 'Phpcode', 'Concatenation')),
                                   1 => array('atom' => array('String', 'Variable', 'Property', 'Array', 'Phpcode', 'Concatenation')),
                                   2 => array('filterOut' => array_merge(Assignation::$operators, array('T_CLOSE_PARENTHESIS', 'T_COMMA')) ),
        ); 
        $this->actions = array('mergeConcat' => "Concat");
        $this->checkAuto();

        return $this->checkRemaining();
    }
}
?>