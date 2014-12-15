<?php

namespace Tokenizer;

class _As extends TokenAuto {
    static public $operators = array('T_AS');
    static public $atom = 'As';

    public function _check() {
        // use C::Const as string
        $this->conditions = array( -1 => array('atom'  => 'Staticconstant'), 
                                    0 => array('token' => _As::$operators,
                                               'atom'  => 'none'),
                                    1 => array('token' => 'T_STRING')
        );
        
        $this->actions = array('transform'    => array( 1 => 'RIGHT',
                                                       -1 => 'LEFT'),
                               'atom'         => 'As',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it' );
        $this->checkAuto();

        // use A as B (adds rank)
        $this->conditions = array( -2 => array('notToken' => 'T_NS_SEPARATOR'),
                                   -1 => array('atom'     => 'Identifier'), 
                                    0 => array('token'    => _As::$operators,
                                               'atom'     => 'none'),
                                    1 => array('token'    => array('T_STRING', 'T_PUBLIC', 'T_PROTECTED', 'T_PRIVATE'))
        );
        
        $this->actions = array('transform'    => array( 1 => 'AS',
                                                       -1 => 'SUBNAME'),
                               'atom'         => 'As',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               'rank'         => array(-1 => '0'));
        $this->checkAuto();
        
        return false;
    } 
    
    public function fullcode() {
        return <<<GREMLIN

s = [];
fullcode.out("SUBNAME").sort{it.rank}._().each{ 
    s.add(it.getProperty('code')); 
};
if (fullcode.absolutens == 'true') {
    s =  '\\\\' + s.join('\\\\');
} else {
    s = s.join('\\\\');
}

fullcode.setProperty('fullcode', s + " as " + fullcode.out("AS").next().getProperty('fullcode'));
fullcode.out('AS').filter{ it.token in [ 'T_PUBLIC', 'T_PROTECTED', 'T_PRIVATE']}.each{ 
    it.setProperty('fullcode', it.code); 
    it.setProperty('atom', 'Ppp'); 
}

GREMLIN;
    }
}
?>
