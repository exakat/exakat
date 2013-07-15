<?php

namespace Tokenizer;

class Sign extends TokenAuto {
    static public $operands = array('Integer', 'Sign', 'String', 'Variable', 'Array', 'Float', 'Boolean', 'Functioncall',
                                    'Staticmethodcall', 'Staticproperty');
    function _check() {

        $this->conditions = array( -1 => array('filterOut2' => array('T_STRING', 'T_LNUMBER', 'T_DNUMBER', 'T_CLOSE_PARENTHESIS', 'T_VARIABLE',)), 
                                   0  => array('token' => array('T_PLUS', 'T_MINUS'),
                                               'atom' => 'none'),
                                   1  => array('atom' => Sign::$operands),
                                   2  => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',
                                                                    'T_OPEN_CURLY', 'T_OPEN_BRACKET')),
                                 );
        
        $this->actions = array('makeEdge'    => array( 1 => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true,)
                               );
        $r = $this->checkAuto();

        $this->conditions = array( -1 => array('token' => array('T_PLUS', 'T_MINUS', 'T_STAR', 'T_SLASH', 'T_PERCENTAGE')), 
                                    0 => array('token' => array('T_PLUS', 'T_MINUS'),
                                               'atom' => 'none'),
                                    1 => array('atom' => Sign::$operands),
                                 );
        
        $this->actions = array('makeEdge'   => array( 1 => 'SIGN'),
                               'atom'       => 'Sign',
                               'property'   => array('scalar' => true,
                                                     'instruction' => true,)
                               );
        
        return $r && $this->checkAuto();
    }
}

?>