<?php

namespace Tokenizer;

class Staticclass extends TokenAuto {
    static public $operators = array('T_DOUBLE_COLON');
    static public $atom = 'Staticclass';

    public function _check() {
        $this->conditions = array( -2 => array('filterOut2' => array('T_NS_SEPARATOR')),
                                   -1 => array('atom'       => array('Constant', 'Identifier', 'Variable', 'Array', 'Static', 'Nsname')), 
                                    0 => array('token'      => Staticconstant::$operators),
                                    1 => array('token'      => 'T_CLASS'), 
                                    2 => array('filterOut'  => array('T_DOUBLE_COLON', 'T_OPEN_PARENTHESIS')),
                                 );
        
        $this->actions = array('transform'    => array( -1 => 'CLASS',
                                                         1 => 'CONSTANT'),
                               'atom'         => 'Staticclass',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it' );
        $this->checkAuto(); 

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.out('CONSTANT').each{ 
    it.setProperty('fullcode',  it.code); 
    it.setProperty('atom',  'Identifier'); 
}
fullcode.setProperty('fullcode',  it.out("CLASS").next().getProperty('fullcode') + "::" + it.out("CONSTANT").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
