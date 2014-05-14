<?php

namespace Tokenizer;

class _Dowhile extends TokenAuto {
    static public $operators = array('T_DO');
    static public $atom = 'Dowhile';

    public function _check() {
        // do ; while() (no block...)
        $this->conditions = array( 0 => array('token' => _Dowhile::$operators),
                                   1 => array('atom'  => 'yes'),
                                   2 => array('token'  => 'T_SEMICOLON'),
                                   3 => array('token' => 'T_WHILE',
                                              'dowhile' => 'true'),
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
                                   2 => array('token' => 'T_WHILE',
                                              'dowhile' => 'true'),
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
                                   1 => array('atom'  => 'Sequence'),
                                   2 => array('token' => 'T_WHILE',
                                              'dowhile' => 'true'),
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

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', "do " + fullcode.out("LOOP").next().getProperty('fullcode') + " while " + fullcode.out("CONDITION").next().getProperty('fullcode'));

GREMLIN;

    }
}

?>