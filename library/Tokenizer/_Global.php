<?php

namespace Tokenizer;

class _Global extends TokenAuto {
    static public $operators = array('T_GLOBAL');
    static public $atom = 'Global';

    public function _check() {
    // global $x; (nothing more)
        $this->conditions = array( 0 => array('token' => _Global::$operators),
                                   1 => array('atom' => array('Variable', 'String', 'Staticconstant', 'Static' )),
                                   2 => array('token' => 'T_SEMICOLON'),
                                 );
        
        $this->actions = array('transform'    => array( 1 => 'NAME'),
                               'atom'         => 'Global',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it'
                               );
        $this->checkAuto(); 

    // global $x, $y, $z;
        $this->conditions = array( 0 => array('token'     => _Global::$operators),
                                   1 => array('atom'      => 'Arguments'),
                                   2 => array('filterOut' => 'T_COMMA'),
                                 );
        
        $this->actions = array('to_global'   => 'Global',
                               'keepIndexed' => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

if (fullcode.out('NAME').count() == 1) {
    fullcode.setProperty('fullcode', "global " + fullcode.out("NAME").next().getProperty('fullcode'));
}

GREMLIN;
    }

}
?>