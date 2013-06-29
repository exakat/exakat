<?php

namespace Tokenizer;

class Arrayappend extends TokenAuto {
    function _check() {
        $this->conditions = array(-1 => array('atom' => array('Variable', 'Property', 'Staticproperty', 'Array')),
                                  0 => array('token' => 'T_OPEN_BRACKET'),
                                  1 => array('token' => 'T_CLOSE_BRACKET'),
        );
        
        $this->actions = array('transform'    => array(  -1 => 'VARIABLE',
                                                         '1' => 'DROP'),
                               'atom'       => 'Arrayappend');
                               
        return $this->checkAuto();
    }
}

?>