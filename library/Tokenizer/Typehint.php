<?php

namespace Tokenizer;

class Typehint extends TokenAuto {
    function _check() {
        $this->conditions = array(-2 => array('filterOut' => 'T_CATCH'),
                                   0 => array('atom' => 'String'),
                                   1 => array('atom' => array('Variable', 'Assignation', 'Reference'    )),
                                   2 => array('filterOut' => Assignation::$operators),
        );
        
        $this->actions = array('to_typehint'  => true,
                               'atom'         => 'Typehint');
        $this->checkAuto();

        $this->conditions = array(-2 => array('filterOut' => 'T_CATCH'),
                                   0 => array('token' => 'T_ARRAY'),
                                   1 => array('atom' => array('Variable', 'Assignation', 'Reference'    )),
                                   2 => array('filterOut' => Assignation::$operators),
        );
        
        $this->actions = array('to_typehint'  => true,
                               'atom'         => 'Typehint');
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>