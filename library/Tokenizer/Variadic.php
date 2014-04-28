<?php

namespace Tokenizer;

class Variadic extends TokenAuto {
    static public $operators = array('T_ELLIPSIS');
    static public $atom = 'While';

    protected $phpversion = '5.6+';

    public function _check() { 
        $this->conditions = array( 0 => array('token' => Variadic::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => array('Variable'))
        );
        
        $this->actions = array('transform'    => array( 0 => 'DROP'),
                               'propertyNext' => array('variadic' => 'true'),
                               'fullcode'     => true,
                               );
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "..." + fullcode.code; 

GREMLIN;
    }
}

?>