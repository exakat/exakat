<?php

namespace Tokenizer;

class Typehint extends TokenAuto {
    static public $operators = array('T_COMMA', 'T_OPEN_PARENTHESIS');
    
    function _check() {
        $this->conditions = array(-1 => array('filterOut' => 'T_CATCH'),
                                   0 => array('token' => Typehint::$operators),
                                   1 => array('atom' => 'yes', 'token' => 'T_STRING'),
                                   2 => array('atom' => array('Variable', 'Assignation', 'Reference'    )),
                                   3 => array('filterOut' => Assignation::$operators),
        );
        
        $this->actions = array('to_typehint'  => true,
                               'keepIndexed'  => true);
        $this->checkAuto();

        $this->conditions = array(-2 => array('filterOut' => 'T_CATCH'),
                                   0 => array('token' => Typehint::$operators),
                                   1 => array('token' => 'T_ARRAY', 'atom' => 'none'),
                                   2 => array('atom' => array('Variable', 'Assignation', 'Reference'    )),
                                   3 => array('filterOut' => Assignation::$operators),
        );
        
        $this->actions = array('to_typehint'  => true,
                               'keepIndexed'  => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>