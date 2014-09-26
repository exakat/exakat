<?php

namespace Tokenizer;

class _Finally extends TokenAuto {
    static public $operators = array('T_FINALLY');
    static public $atom = 'Finally';

    public function _check() {
        $this->conditions = array(0 => array('token' => _Finally::$operators,
                                             'atom'  => 'none'),
                                  1 => array('atom'  => 'Sequence',
                                             'property' => array('block' => 'true')),
                                  );
        
        $this->actions = array('transform'  => array( 1 => 'CODE' ),
                               'cleanIndex' => true,
                               'atom'       => 'Finally');
                               
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN
fullcode.setProperty('fullcode', "finally " + fullcode.out("CODE").next().getProperty('fullcode')); 
GREMLIN;
    }
}

?>