<?php

namespace Tokenizer;

class Concatenation extends TokenAuto {
    function _check() {
        $operands = array('String', 'Integer', 'Float', 'Not', 'Variable','_Array', 'Concatenation', 'Sign', 'Array',
                          'Functioncall', 'Noscream', 'Staticproperty', 'Staticmethodcall', 'Staticconstant' );
        
        $this->conditions = array(-2 => array('filterOut' => array('T_AT', 'T_NOT', 'T_DOUBLE_COLON')), 
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => 'T_DOT',
                                              'atom'  => 'none'),
                                   1 => array('atom'  => $operands),
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

        $this->conditions = array( 0 => array('atom' => array('String', 'Variable')),
                                   1 => array('atom' => array('String', 'Variable')),
        ); 

        $this->actions = array('insertConcat'   => "concat scalar",
                                'order' => array(0 => 1, 
                                                 1 => 2),
                                );
        $r = $this->checkAuto();

        $this->conditions = array( 0 => array('atom' => array('Concatenation')),
                                   1 => array('atom' => array('Concatenation')),
        ); 

        $this->actions = array('insertConcat2'    => "concat concat" );
        $r = $this->checkAuto();


        $this->conditions = array( 0 => array('atom' => array('Concatenation')),
                                   1 => array('atom' => array('String', 'Variable')),
        ); 

        $this->actions = array('insertConcat3'   => true,
                                'order' => array(0 => 1, 
                                                 1 => 2),
                                );

        $r = $this->checkAuto();

/*
        $this->conditions = array( 0 => array('atom' => array('Concatenation')),
                                   1 => array('atom' => array('Concatenation')),
        ); 

        $this->actions = array('insertConcat3'   => true,
                                'order' => array(0 => 1, 
                                                 1 => 2),
                                'mergeNext'  => array('Concatenation' => 'CONCAT'), 
                                );
        $r =  $this->checkAuto();
*/
// Case of string with interpolation : "a${b}c";
        $this->conditions = array( -1 => array('token' => array('T_QUOTE'), 'atom' => 'none'),
                                    0 => array('atom'  => array('Concatenation', 'String')),
                                    1 => array('token' => array('T_QUOTE'), 'atom' => 'none')
                                 );
        
        $this->actions = array( 'transform' => array(-1 => 'DROP',
                                                      1 => 'DROP'),
                                'atom'       => 'Concatenation',
                                'mergeNext'  => array('Concatenation' => 'CONCAT'), 
                                );
        $r =  $this->checkAuto();
//        $this->printQuery();

        return $r;
    }

    function reserve() {
        Token::$reserved[] = 'T_DOT';
    }
}
?>