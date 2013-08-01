<?php

namespace Tokenizer;

class _While extends TokenAuto {
    static public $operators = array('T_WHILE');

    function _check() {
        $this->conditions = array(-1 => array('filterOut2' => array('T_CLOSE_CURLY', 'T_OPEN_CURLY')),
                                   0 => array('token' => _While::$operators),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => 'T_SEMICOLON', 'atom' => 'none'),
        );
        
        $this->actions = array('while_to_block'    => true,
                               'keepIndexed'       => true);
        $this->checkAuto();        

        $this->conditions = array(0 => array('token' => _While::$operators),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'yes'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('atom'  => 'Block'),
        );
        
        $this->actions = array('transform'    => array(  1 => 'DROP',
                                                         2 => 'CONDITION',
                                                         3 => 'DROP',
                                                         4 => 'LOOP',      ),
                               'atom'       => 'While');
        $this->checkAuto();
        
        $this->conditions = array(-2 => array('filterOut2' => array('T_CLOSE_PARENTHESIS', 'T_OPEN_PARENTHESIS', 'T_DO',)),
                                  -1 => array('atom'  => 'Block'),
                                   0 => array('token' => _While::$operators),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
        );
        
        $this->actions = array('transform'    => array( -1 => 'LOOP',
                                                         1 => 'DROP',
                                                         2 => 'CONDITION',
                                                         3 => 'DROP',
                                                        ),
                               'atom'       => 'While');
        $this->checkAuto();

        $this->conditions = array(0 => array('token' => _While::$operators),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'yes'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token' => 'T_COLON'),
                                  5 => array('atom'  => 'yes'),
                                  6 => array('token'  => 'T_ENDWHILE'),
        );
        
        $this->actions = array('transform'    => array(  1 => 'DROP',
                                                         2 => 'CONDITION',
                                                         3 => 'DROP',
                                                         4 => 'DROP',
                                                         5 => 'LOOP',
                                                         6 => 'DROP',
                                                        ),
                               'atom'       => 'While');
                               
        $this->checkAuto();

/*
        $this->conditions = array(-5 => array('filterOut2' => array('T_CLOSE_CURLY', 'T_OPEN_CURLY')),
                                  -4 => array('token' => 'T_WHILE'),
                                  -3 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  -2 => array('atom'  => 'yes'),
                                  -1 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   0 => array('token' => 'T_SEMICOLON', 'atom' => 'none'),
        );
        
        $this->actions = array('transform'    => array(1 => 'BLOCK'),
                               'atom' => 'Block');
        $this->checkAuto();*/
        
        return $this->checkRemaining();
    }
}

?>