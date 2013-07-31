<?php

namespace Tokenizer;

class Postplusplus extends TokenAuto {
    static public $operators = array('T_INC', 'T_DEC');
    
    function _check() {
        $this->conditions = array(-2 => array('filterOut' => array('T_OBJECT_OPERATOR')),
                                  -1 => array('atom' => array('Variable', 'Array', 'Property', 'Functioncall', 'Staticproperty' )),
                                   0 => array('token' => Postplusplus::$operators),
                                   1 => array('filterOut' => array('T_DOUBLECOLON')),
        );
        
        $this->actions = array('transform'    => array( '-1' => 'POSTPLUSPLUS'),
                               'atom'       => 'Postplusplus');
                               
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>