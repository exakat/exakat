<?php

namespace Tokenizer;

class _Const extends TokenAuto {
    static public $operators = array('T_CONST');
    static public $atom = 'Const';

    public function _check() {
    // class x { const a = 2, b = 2, c = 3; }
        $this->conditions = array( -1 => array('notToken'  => 'T_USE'),
                                    0 => array('token'     => _Const::$operators),
                                    1 => array('atom'      => 'Arguments'),
                                    2 => array('filterOut' => 'T_COMMA'),
                                 );
        
        $this->actions = array('to_const'     => true);
        $this->checkAuto(); 

    // class x {const a = 2; } only one.
        $this->conditions = array( -1 => array('notToken' => 'T_USE'),
                                    0 => array('token'    =>  _Const::$operators),
                                    1 => array('atom'     => 'Assignation'),
                                    2 => array('token'    => 'T_SEMICOLON')
                                 );
        
        $this->actions = array('to_const_assignation' => true,
                               'atom'                 => 'Const',
                               'cleanIndex'           => true,
                               'makeSequence'         => 'it');
        $this->checkAuto(); 

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', "const " + fullcode.out("NAME").next().getProperty('code') + " = " + fullcode.out("VALUE").next().getProperty('fullcode'));

GREMLIN;
    }
}
?>