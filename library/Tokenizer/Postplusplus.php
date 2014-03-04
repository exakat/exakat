<?php

namespace Tokenizer;

class Postplusplus extends TokenAuto {
    static public $operators = array('T_INC', 'T_DEC');
    
    public function _check() {
        $this->conditions = array(-2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                  -1 => array('atom' => array('Variable', 'Array', 'Property', 'Functioncall', 'Staticproperty' )),
                                   0 => array('token' => Postplusplus::$operators),
                                   1 => array('filterOut' => array('T_DOUBLECOLON')),
        );
        
        $this->actions = array('transform'  => array( -1 => 'POSTPLUSPLUS'),
                               'atom'       => 'Postplusplus',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = it.out("POSTPLUSPLUS").next().fullcode + it.code; ';
    }

}

?>