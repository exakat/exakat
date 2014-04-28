<?php

namespace Tokenizer;

class Halt extends TokenAuto {
    static public $operators = array('T_HALT_COMPILER');

    public function _check() {
        $this->conditions = array(0 => array('token' => Halt::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  );
        
        $this->actions = array('transform'    => array(2 => 'DROP',
                                                       1 => 'DROP'),
                               'atom'         => 'Halt',
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

        $this->conditions = array(0 => array('token' => Halt::$operators,
                                             'atom'  => 'none'),
                                  1 => array('notToken' => 'T_OPEN_PARENTHESIS')
                                  );
        
        $this->actions = array('atom'         => 'Halt',
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
    
    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.code);

GREMLIN;
    }
}

?>