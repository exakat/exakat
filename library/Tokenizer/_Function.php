<?php

namespace Tokenizer;

class _Function extends TokenAuto {
    static public $operators = array('T_FUNCTION');
    
    function _check() {
        $this->conditions = array(0 => array('token' => _Function::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('String', 'Reference', 'Boolean')),
                                  2 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  3 => array('atom' => 'Arguments'),
                                  4 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  5 => array('atom' => 'Block'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAME',
                                                        2 => 'DROP',
                                                        3 => 'ARGUMENTS',
                                                        4 => 'DROP', 
                                                        5 => 'BLOCK'),
                               'atom'       => 'Function',
                               'cleanIndex' => true);
        $this->checkAuto();

        $this->conditions = array(0 => array('token' =>  _Function::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('String', 'Reference', 'Boolean')),
                                  2 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  3 => array('atom' => 'Arguments'),
                                  4 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  5 => array('token' => 'T_SEMICOLON'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAME',
                                                        2 => 'DROP',
                                                        3 => 'ARGUMENTS',
                                                        4 => 'DROP', 
                                                        5 => 'DROP'),
                               'atom'       => 'Function',
                               'cleanIndex' => true);
        $this->checkAuto();

        // lambda function (no name)
        $this->conditions = array(0 => array('token' =>  _Function::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom' => 'Arguments'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('atom' => 'Block')
        );
        
        $this->actions = array('to_lambda'  => true,
                               'atom'       => 'Function',
                               'cleanIndex' => true);
        $this->checkAuto();

        // lambda function ($x) use ($y)
        $this->conditions = array(0 => array('token' =>  _Function::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'Arguments'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token' => 'T_USE'),
                                  5 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  6 => array('atom'  => 'Arguments'),
                                  7 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  8 => array('atom'  => 'Block')
        );
        
        $this->actions = array('to_lambda_use'  => true,
                               'atom'       => 'Function',
                               'cleanIndex' => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>