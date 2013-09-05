<?php

namespace Tokenizer;

class _Declare extends TokenAuto {
    static public $operators = array('T_DECLARE');

    function _check() {
        // declare(ticks = 2) : block endblock;
        $this->conditions = array(0 => array('token' => _Declare::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'Arguments'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token' => 'T_COLON'),
                                  5 => array('atom'  => 'yes'),
                                  6 => array('token' => 'T_ENDDECLARE'),
        );
        
        $this->actions = array('transform'  => array( 1 => 'DROP',
                                                      2 => 'TICKS',
                                                      3 => 'DROP',
                                                      4 => 'DROP',
                                                      5 => 'BLOCK',
                                                      6 => 'DROP',
                                                      ),
                               'atom'       => 'Declare',
                               'cleanIndex' => true);
        $this->checkAuto();

        // declare(ticks = 2); 
        $this->conditions = array(0 => array('token' => _Declare::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'Arguments'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token'  => 'T_SEMICOLON')
        );
        
        $this->actions = array('transform'  => array( 1 => 'DROP',
                                                      2 => 'TICKS',
                                                      3 => 'DROP',
                                                      4 => 'DROP'),
                               'atom'       => 'Declare',
                               'cleanIndex' => true);
        $this->checkAuto();

        // declare(ticks = 2) { block }
        $this->conditions = array(  0 => array('token' => _Declare::$operators),
                                    1 => array('atom'  => 'none',
                                               'token' => 'T_OPEN_PARENTHESIS' ),
                                    2 => array('atom'  =>  array('Arguments', 'Void')),
                                    3 => array('atom'  => 'none',
                                               'token' => 'T_CLOSE_PARENTHESIS' ),
                                    4 => array('atom'  => array('Block')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'TICKS',
                                                        3 => 'DROP',
                                                        4 => 'BLOCK'),
                               'atom'       => 'Declare',
                               'cleanIndex' => true,
                               );
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>