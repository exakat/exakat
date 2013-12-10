<?php

namespace Tokenizer;

class _Continue extends TokenAuto {
    static public $operators = array('T_CONTINUE');

    function _check() {
        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_SEMICOLON')
                                  );
        
        $this->actions = array('addEdge'     => array(0 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true);
                               
        $this->checkAuto();

        $this->conditions = array(0 => array('token' => _Continue::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Integer', 'Void'))
                                  );
        
        $this->actions = array('transform'    => array( '1' => 'LEVEL'),
                               'atom'       => 'Continue');
        $this->checkAuto();

        return $this->checkRemaining();
    }

    function fullcode() {
        return 'it.fullcode = "continue " + it.out("LEVEL").next().code; ';
    }
}

?>