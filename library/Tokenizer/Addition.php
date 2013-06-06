<?php

namespace Tokenizer;

class Addition extends TokenAuto {
    function check() {
        $operands = array('Integer', 'Addition', 'Variable', 'Multiplication','Sign','Not');
        
        $this->conditions = array(-1 => array('atom' => $operands ),
                                  0 => array('code' => array('+','-'),
                                             'atom' => 'none'),
                                  1 => array('atom' => $operands),
        );
        
        $this->actions = array('makeEdge'    => array('1' => 'RIGHT',
                                                      '-1' => 'LEFT'
                                                      ),
                               'atom'       => 'Addition',
                               );
//        $this->printQuery();
        return $this->checkAuto();
    }
}
?>