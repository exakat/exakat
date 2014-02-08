<?php

namespace Tokenizer;

class _Dowhile extends TokenAuto {
    static public $operators = array('T_DO');

    function _check() {
        // do ; while() (no block...)
        $this->conditions = array( 0 => array('token' => _Dowhile::$operators),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('token'  => 'T_SEMICOLON'),
                                   3 => array('token' => 'T_WHILE'),
                                   4 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   5 => array('atom'  => 'yes'),
                                   6 => array('token' => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('transform'    => array(   1 => 'LOOP',  
                                                          2 => 'DROP',
                                                          3 => 'DROP',
                                                          4 => 'DROP',
                                                          5 => 'CONDITION',
                                                          6 => 'DROP'
                                                        ),
                               'atom'       => 'Dowhile',
                               'cleanIndex' => true);
        $this->checkAuto();

        // do if() {} while() (no block...)
        $this->conditions = array( 0 => array('token' => _Dowhile::$operators),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('token' => 'T_WHILE'),
                                   3 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   4 => array('atom'  => 'yes'),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('transform'    => array(   1 => 'LOOP',  
                                                          2 => 'DROP',
                                                          3 => 'DROP',
                                                          4 => 'CONDITION',
                                                          5 => 'DROP'
                                                        ),
                               'atom'       => 'Dowhile',
                               'cleanIndex' => true);
        $this->checkAuto();

        // do { block } while()
        $this->conditions = array( 0 => array('token' => _Dowhile::$operators),
                                   1 => array('atom'  => 'Block'),
                                   2 => array('token' => 'T_WHILE'),
                                   3 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   4 => array('atom'  => 'yes'),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS')
        );
        
        $this->actions = array('transform'    => array(   1 => 'LOOP',  
                                                          2 => 'DROP',
                                                          3 => 'DROP',
                                                          4 => 'CONDITION',
                                                          5 => 'DROP'
                                                        ),
                               'atom'       => 'Dowhile',
                               'cleanIndex' => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }

    function fullcode() {
        return 'it.fullcode = "do " + it.out("LOOP").next().fullcode + " while " + it.out("CONDITION").next().fullcode;';
    }
}

?>