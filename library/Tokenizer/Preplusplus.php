<?php

namespace Tokenizer;

class Preplusplus extends TokenAuto {
    static public $operators = array('T_INC', 'T_DEC');
    
    public function _check() {
        $this->conditions = array( 0 => array('token' => Preplusplus::$operators),
                                   1 => array('atom' => array('Variable', 'Array', 'Property', 'Functioncall', 'Staticproperty' )),
                                   2 => array('filterOut' => array('T_DOUBLECOLON', 'T_OBJECT_OPERATOR', 'T_OPEN_BRACKET', 'T_OPEN_PARENTHESIS', 'T_DOUBLE_COLON', 'T_OBJECT_OPEARTOR' )),
        );
        
        $this->actions = array('transform'    => array( 1 => 'PREPLUSPLUS'),
                               'atom'       => 'Preplusplus',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = it.code + it.out("PREPLUSPLUS").next().fullcode; ';
    }
}

?>