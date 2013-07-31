<?php

namespace Tokenizer;

class Arrayappend extends TokenAuto {
    static public $operators = array('T_OPEN_BRACKET');
    
    function _check() {
        $this->conditions = array(-1 => array('atom' => array('Variable', 'Property', 'Staticproperty', 'Array')),
                                   0 => array('token' => Arrayappend::$operators),
                                   1 => array('token' => 'T_CLOSE_BRACKET'),
        );
        
        $this->actions = array('transform'    => array(  -1 => 'VARIABLE',
                                                         '1' => 'DROP'),
                               'atom'       => 'Arrayappend');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>