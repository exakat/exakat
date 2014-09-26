<?php

namespace Tokenizer;

class _Yield extends TokenAuto {
    static public $operators = array('T_YIELD');
    static public $atom = 'Yield';

    public function _check() {
        $this->conditions = array(0 => array('token' => _Yield::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => 'yes'),
                                  2 => array('token' => 'T_SEMICOLON')
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'YIELD' ),
                               'cleanIndex'   => true,
                               'atom'         => 'Yield',
                               'makeSequence' => 'it');
                               
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN
fullcode.setProperty('fullcode', "yield " + fullcode.out("YIELD").next().getProperty('fullcode')); 
GREMLIN;
    }
}

?>