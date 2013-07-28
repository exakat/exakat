<?php

namespace Tokenizer;

class SequenceCaseDefault extends TokenAuto {
    function _check() {
        // special case for Case and default, that are in a switch statement. 
        $operands = array('Case', 'Default', 'SequenceCaseDefault');

        $this->conditions = array( 0 => array('atom' => $operands),
                                   1 => array('atom' => $operands),
        );
        $this->actions = array('insertSequenceCaseDefault'  => true);
        $r = $this->checkAuto();
        
        return $r;
    }
}
?>