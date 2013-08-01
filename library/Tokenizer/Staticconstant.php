<?php

namespace Tokenizer;

class Staticconstant extends TokenAuto {
    static public $operators = array('T_DOUBLE_COLON');

    function _check() {
        $this->conditions = array( -1 => array('atom' => array('Constant', 'String', 'Variable', 'Array')), 
                                    0 => array('token' => Staticconstant::$operators),
                                    1 => array('atom' => array('Constant', 'String')), 
                                    2 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS')),
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'CLASS',
                                                       1 => 'CONSTANT'),
                               'atom'       => 'Staticconstant',
                               'cleanIndex' => true );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>