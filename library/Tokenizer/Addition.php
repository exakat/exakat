<?php

namespace Tokenizer;

class Addition extends TokenAuto {
    function check() {
        $operands = array('Integer', 'Addition', 'Variable', 'Multiplication');
        
        $this->conditions = array(-1 => array('atom' => $operands ),
                                  0 => array('code' => array('+','-'),
                                             'atom' => 'none'),
                                  1 => array('atom' => $operands),
        );
        
        $this->actions = array('addEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'),
                               'changeNext' => array(1, -1),
                               'atom'       => 'Addition',
                               'cleansemicolon' => 1);
        return $this->checkAuto();
    }
}
?>