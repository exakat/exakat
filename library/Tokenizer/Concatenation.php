<?php

namespace Tokenizer;

class Concatenation extends TokenAuto {
    function _check() {
        $operands = array('String', 'Integer', 'Float', 'Not', 'Variable','_Array', 'Concatenation', 'Sign', 'Array',
                          'Functioncall', 'Noscream', 'Staticproperty', 'Staticmethodcall' );
        
        $this->conditions = array(-2 => array('filterOut' => array('T_AT', 'T_NOT', 'T_DOUBLE_COLON')), 
                                  -1 => array('atom' => $operands ),
                                   0 => array('code' => '.',
                                             'atom' => 'none'),
                                   1 => array('atom' => $operands),
        ); 
        
        $this->actions = array('makeEdge'    => array('1' => 'CONCAT',
                                                      '-1' => 'CONCAT'
                                                      ),
                               'order'    => array('1'  => '2',
                                                   '-1' => '1'
                                                      ),
                               'mergeNext'  => array('Concatenation', 'CONCAT'), 
                               'atom'       => 'Concatenation',
                               );
        
        return $this->checkAuto();
    }

    function reserve() {
        Token::$reserved[] = 'T_DOT';
    }
}
?>