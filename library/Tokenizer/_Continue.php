<?php

namespace Tokenizer;

class _Continue extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_CONTINUE',
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_SEMICOLON')
                                  );
        
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'LEVEL')));
                               
        $r = $this->checkAuto();

        $this->conditions = array(0 => array('token' => 'T_CONTINUE',
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Integer', 'Void'))
                                  );
        
        $this->actions = array('transform'    => array( '1' => 'LEVEL'),
                               'atom'       => 'Continue');
                               
        $r = $this->checkAuto();

        return $r;
    }
}

?>