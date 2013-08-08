<?php

namespace Tokenizer;

class _Break extends TokenAuto {
    static public $operators = array('T_BREAK');
    
    function _check() {
        $this->conditions = array(0 => array('token' => _Break::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_SEMICOLON')
                                  );
        
        $this->actions = array('addEdge'     => array(0 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true
                               );
//        $this->checkAuto();

        $this->conditions = array(0 => array('token' => _Break::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Integer', 'Void'))
                                  );
        
        $this->actions = array('transform'  => array( 1 => 'LEVEL'),
                               'atom'       => 'Break');
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>