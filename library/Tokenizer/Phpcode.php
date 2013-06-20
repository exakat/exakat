<?php

namespace Tokenizer;

class Phpcode extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_OPEN_TAG',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_CLOSE_TAG'),
        );
        
        $this->actions = array('transform'    => array( '1' => 'CODE',
                                                        '2' => 'DROP'),
                               'atom'       => 'Phpcode');
        $r = $this->checkAuto();

        $this->conditions = array(0 => array('token' => 'T_OPEN_TAG',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_END'),
        );
        
        $this->actions = array('transform'    => array( '1' => 'CODE'),
                               'atom'       => 'Phpcode');
        $r = $this->checkAuto();
        
        return $r;
    }
}

?>