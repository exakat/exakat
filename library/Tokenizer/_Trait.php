<?php

namespace Tokenizer;

class _Trait extends TokenAuto {
    static public $operators = array('T_TRAIT');
    static public $atom = 'Trait';
    
    protected $phpversion = '5.4+';

    public function _check() {
        $this->conditions = array(0 => array('token' => _Trait::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Identifier'),
                                  2 => array('atom' => 'Sequence'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NAME',
                                                        2 => 'BLOCK'),
                               'atom'         => 'Trait',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "trait " + fullcode.out("NAME").next().code; 

GREMLIN;
    }

}

?>