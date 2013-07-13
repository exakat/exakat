<?php

namespace Tokenizer;

class _Var extends TokenAuto {
    function _check() {
    
    // class x { var $x }
        $this->conditions = array( 0 => array('token' => 'T_VAR'),
                                   1 => array('atom' => array('Variable', 'String', 'Staticconstant')),
                                   2 => array('filterOut' => array('T_EQUAL', 'T_COMMA')),
                                   // T_SEMICOLON because of _Class 28 test
                                 );
        
        $this->actions = array('transform' => array( 1 => 'DEFINE'),
                               'add_void'  => array( 0 => 'VALUE'), 
                               'atom'      => 'Var',
                               );

        $r = $this->checkAuto(); 

    // class x { var $x = 2 }
        $this->conditions = array( 0 => array('token' => 'T_VAR'),
                                   1 => array('atom' => 'Variable'),
                                   2 => array('token' => 'T_EQUAL'),
                                   3 => array('atom' => array('String,', 'Integer', 'Staticconstant', 'Functioncall',)),
                                   4 => array('filterOut2' => array('T_COMMA')),
                                 );
        
        $this->actions = array('transform'   => array(   1 => 'DEFINE',
                                                         2 => 'DROP',
                                                         3 => 'VALUE'),
                               'atom'       => 'Var',
                               );

        $r = $this->checkAuto(); 

    // class x { var $x, $y }
        $this->conditions = array( 0 => array('token' => 'T_VAR'),
                                   1 => array('atom' => 'Arguments'),
                                 );
        
        $this->actions = array('to_var'   => true,
                               'atom'       => 'Var',
                               );

        $r = $this->checkAuto(); 

        return $r;
    }
}
?>