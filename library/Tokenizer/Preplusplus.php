<?php

namespace Tokenizer;

class Preplusplus extends TokenAuto {
    static public $operators = array('T_INC', 'T_DEC');
    static public $atom = 'Preplusplus';
    
    public function _check() {
        $this->conditions = array( 0 => array('token' => Preplusplus::$operators),
                                   1 => array('atom' => array('Variable', 'Array', 'Property', 'Functioncall', 'Staticproperty' )),
                                   2 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_OPEN_BRACKET', 'T_OPEN_PARENTHESIS')),
        );
        
        $this->actions = array('transform'  => array( 1 => 'PREPLUSPLUS'),
                               'atom'       => 'Preplusplus',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.getProperty('code') + fullcode.out("PREPLUSPLUS").next().getProperty('fullcode')); 

GREMLIN;
    }
}

?>