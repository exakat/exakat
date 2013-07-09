<?php

namespace Tokenizer;

class Label extends TokenAuto {
    function _check() {
        $this->conditions = array(-2 => array('filterOut' => array('T_QUESTION','T_CASE') ), 
                                  -1 => array('atom' => 'String'),
                                   0 => array('token' => 'T_COLON')
                                  );
        
        $this->actions = array('transform'   => array(-1 => 'LABEL'),
                               'atom' => 'Label');
                               
        $r = $this->checkAuto();

        return $r;
    }
}

?>