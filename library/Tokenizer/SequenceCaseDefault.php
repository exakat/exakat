<?php

namespace Tokenizer;

class SequenceCaseDefault extends TokenAuto {
    static public $operators = array('T_SWITCH');
    // @todo set up a new index for storing those and speed up the localisation
    
    function _check() {
        // special case for Case and default, that are in a switch statement. 
        $operands = array('Case', 'Default', 'SequenceCaseDefault');

        $this->conditions = array( 0 => array('atom' => $operands),
                                   1 => array('atom' => $operands),
        );
        $this->actions = array('insertSequenceCaseDefault'  => true,
                               'order'      => array( 0 => 1,
                                                      1 => 2 ),
                               'keepIndexed' => true);
        $r = $this->checkAuto();
        
        return $r;
    }
}
?>