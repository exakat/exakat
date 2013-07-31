<?php

namespace Tokenizer;

class _Dowhile extends TokenAuto {
    static public $operators = array('T_DO');

    function _check() {
        $this->conditions = array(-2 => array('token' => _Dowhile::$operators),
                                  -1 => array('atom'  => 'Block'),
                                   0 => array('token' => 'T_WHILE'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
        );
        
        $this->actions = array('transform'    => array(  -2 => 'LOOP',  // This makes no sense!!
                                                         -1 => 'DROP',
                                                          1 => 'DROP',
                                                          2 => 'CONDITION',
                                                          3 => 'DROP',
                                                        ),
                               'atom'       => 'Dowhile');
                               
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>