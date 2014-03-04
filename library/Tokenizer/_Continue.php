<?php

namespace Tokenizer;

class _Continue extends TokenAuto {
    static public $operators = array('T_CONTINUE');

    public function _check() {
        // continue ;
        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_SEMICOLON')
                                  );
        
        $this->actions = array('addEdge'     => array(0 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true);
                               
        $this->checkAuto();

        // continue 2 ;
        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Integer', 'Void'))
                                  );
        
        $this->actions = array('transform'    => array( '1' => 'LEVEL'),
                               'atom'       => 'Continue');
        $this->checkAuto();

        // continue(2);
        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'Integer'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'LEVEL',
                                                        3 => 'DROP'),
                               'atom'       => 'Continue');
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = "continue " + it.out("LEVEL").next().code; ';
    }
}

?>