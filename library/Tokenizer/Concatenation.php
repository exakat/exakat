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
        
        $r = $this->checkAuto();


        $this->conditions = array( -1 => array('filterOut' => array('T_AT', 'T_NOT', 'T_DOUBLE_COLON', 'T_DOLLAR', 'T_OPEN_PARENTHESIS')),
                                    0 => array('atom' => array('String', 'Variable', 'Property', 'Array',)),
                                    1 => array('atom' => array('String', 'Variable', 'Property', 'Array',)),
                                    2 => array('filterOut' => array_merge( array('T_OPEN_BRACKET'),
                                                                            Assignation::$operators)),
        ); 

        $this->actions = array('insertConcat'   => "Concat",
                                'order' => array(0 => 1, 
                                                 1 => 2),
                                );
        $r = $this->checkAuto();


// Fusion of 2 concatenations
        $this->conditions = array( 0 => array('atom' => array('Concatenation')),
                                   1 => array('atom' => array('Concatenation')),
        ); 

        $this->actions = array('insertConcat2'    => "Concat" );
        $r = $this->checkAuto();


// Concatenation with another string structure
        $this->conditions = array( 0 => array('atom' => array('Concatenation')),
                                   1 => array('atom' => array('String', 'Variable', 'Property', 'Array',)),
        ); 

        $this->actions = array('insertConcat3'   => true,
                                'order' => array(0 => 1, 
                                                 1 => 2),
                                );

        $r = $this->checkAuto();

        $this->conditions = array(  0 => array('atom' => array('String', 'Variable', 'Property', 'Array',)),
                                    1 => array('atom' => array('Concatenation')),
        ); 

        $this->actions = array('insertConcat4'   => true,
                                'order' => array(0 => 1, 
                                                 1 => 2),
                                );

        $r = $this->checkAuto();

// Fusion of string and PHPcode
        $this->conditions = array( 0 => array('atom' => array('String', 'Phpcode' )),
                                   1 => array('atom' => array('String', 'Phpcode' )),
        ); 

        $this->actions = array('insertConcat'    => "Concat" );
        $r = $this->checkAuto();

        return $r;
    }
}
?>