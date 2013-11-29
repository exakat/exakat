<?php

namespace Tokenizer;

class FunctioncallArray extends TokenAuto {
    static public $operators = array('S_ARRAY');

    function _check() {
        
        // $x[3]()
        $this->conditions = array(   0 => array('atom'  => 'Array'),
                                     1 => array('atom'  => 'none',
                                                'token' => 'T_OPEN_PARENTHESIS' ),
                                     2 => array('atom'  =>  array('Arguments', 'Void')),
                                     3 => array('atom'  => 'none',
                                                'token' => 'T_CLOSE_PARENTHESIS'),
        );

        $this->actions = array('transform'   => array(1 => 'DROP',
                                                      2 => 'ARGUMENTS',
                                                      3 => 'DROP'),
                               'atom'        => 'Functioncall' );
//        $this->printQuery();
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}
?>