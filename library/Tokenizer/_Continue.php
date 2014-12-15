<?php

namespace Tokenizer;

class _Continue extends TokenAuto {
    static public $operators = array('T_CONTINUE');
    static public $atom = 'Continue';

    public function _check() {
        // continue ; without nothing behind
        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => array('T_SEMICOLON', 'T_ENDIF'))
                                  );
        
        $this->actions = array('addEdge'     => array(0 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true);
        $this->checkAuto();

        // continue 2 ;
        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Integer', 'Void'))
                                  );
        
        $this->actions = array('transform'  => array( '1' => 'LEVEL'),
                               'atom'       => 'Continue',
                               'makeSequence' => 'it');
        $this->checkAuto();

        // continue(2);
        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'Integer'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  );
        
        $this->actions = array('transform'  => array( 1 => 'DROP',
                                                      2 => 'LEVEL',
                                                      3 => 'DROP'),
                               'atom'       => 'Continue',
                               'makeSequence' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', "continue " + fullcode.out("LEVEL").next().getProperty('code')); 

GREMLIN;
    }
}

?>
