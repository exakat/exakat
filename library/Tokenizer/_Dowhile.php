<?php

namespace Tokenizer;

class _Dowhile extends TokenAuto {
    static public $operators = array('T_DO');

    function _check() {
        $this->conditions = array( 0 => array('token' => _Dowhile::$operators),
                                   1 => array('atom'  => 'Block'),
                                   2 => array('token' => 'T_WHILE'),
                                   3 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   4 => array('atom'  => 'yes'),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS'),
        );
        
        $this->actions = array('transform'    => array(   1 => 'LOOP',  // This makes no sense!!
                                                          2 => 'DROP',
                                                          3 => 'DROP',
                                                          4 => 'CONDITION',
                                                          5 => 'DROP',
                                                        ),
                               'atom'       => 'Dowhile',
                               'cleanIndex' => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>