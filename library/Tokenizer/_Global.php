<?php

namespace Tokenizer;

class _Global extends TokenAuto {
    static public $operators = array('T_GLOBAL');
    static public $atom = 'Global';

    public function _check() {
    // global $x; (nothing more)
        $this->conditions = array( 0 => array('token' => _Global::$operators),
                                   1 => array('atom'  => array('Variable', 'String', 'Staticconstant', 'Static', 'Property' )),
                                   2 => array('token' => 'T_SEMICOLON')
                                 );
        
        $this->actions = array('transform'    => array( 1 => 'GLOBAL'),
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
        
        $this->actions = array('toGlobal'     => true,
                               'atom'         => 'Global',
                               'makeSequence' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

if (fullcode.out('GLOBAL').count() == 1) {
    fullcode.setProperty('fullcode', "global " + fullcode.out("GLOBAL").next().getProperty('fullcode'));
} else {
    s = [];
    fullcode.out("GLOBAL").sort{it.rank}._().each{ s.add(it.fullcode); };

    fullcode.setProperty('fullcode', "global " + s.join(', '));
}

GREMLIN;
    }

}
?>
