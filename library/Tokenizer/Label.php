<?php

namespace Tokenizer;

class Label extends TokenAuto {
    function _check() {
        $this->conditions = array(-2 => array('filterOut' => array('T_QUESTION','T_CASE', 'T_DOT', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON',) ), 
                                  -1 => array('atom' => 'String'),
                                   0 => array('token' => 'T_COLON')
                                  );
        
        $this->actions = array('transform'   => array(-1 => 'LABEL'),
                               'atom' => 'Label');
                               
//        $this->printQuery();
        $r = $this->checkAuto();

        return $r;
    }
}

?>