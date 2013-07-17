<?php

namespace Tokenizer;

class Typehint extends TokenAuto {
    function _check() {
        $this->conditions = array(-2 => array('filterOut' => 'T_CATCH'),
                                   0 => array('atom' => 'String'),
                                   1 => array('atom' => 'Variable'),
        );
        
        $this->actions = array('to_typehint'    => true,
                               'atom' => 'Typehint');
        $r = $this->checkAuto();

        return $r;
    }
}

?>