<?php

namespace Tokenizer;

class _Switch extends TokenAuto {
    static public $operators = array('T_SWITCH');

    public function _check() {
        $this->conditions = array(0 => array('token' => _Switch::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => array('T_OPEN_PARENTHESIS')),
                                  2 => array('atom' => 'yes'),
                                  3 => array('token' => array('T_CLOSE_PARENTHESIS')),
                                  4 => array('atom' => array('Block')),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'NAME',
                                                        3 => 'DROP',
                                                        4 => 'CASES'),
                               'atom'       => 'Switch',
                               'cleanIndex' => true);
        $this->checkAuto();

        // alternative syntax
        $this->conditions = array(0 => array('token' => _Switch::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'yes'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token' => 'T_COLON'),
                                  5 => array('atom'  => array('SequenceCaseDefault')),
                                  6 => array('token' => 'T_ENDSWITCH'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'NAME',
                                                        3 => 'DROP',
                                                        4 => 'DROP',
                                                        5 => 'CASES',
                                                        6 => 'DROP',),
                               'atom'       => 'Switch',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = "switch " + it.out("NAME").next().code + it.out("CASES").next().code; ';
    }
}

?>