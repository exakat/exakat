<?php

namespace Tokenizer;

class _Switch extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_SWITCH',
                                             'atom' => 'none'),
                                  1 => array('token' => array('T_OPEN_PARENTHESIS')),
                                  2 => array('atom' => 'yes'),
                                  3 => array('token' => array('T_CLOSE_PARENTHESIS')),
                                  4 => array('atom' => array('Block')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'NAME',
                                                        3 => 'DROP',
                                                        4 => 'CASES'),
                               'atom'       => 'Switch');
                               
        return $this->checkAuto();
    }
}

?>