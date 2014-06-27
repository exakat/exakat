<?php

namespace Tokenizer;

class Staticmethodcall extends TokenAuto {
    static public $operators = array('T_DOUBLE_COLON');
    static public $atom = 'Staticmethodcall';

    public function _check() {
        $operands = array('Constant', 'Identifier', 'Variable', 'Array', 'Nsname', 'Static', 'Nsname',);

        // unusual call : Class::{Method}(); Only build the Functioncall
        $this->conditions = array( -2 => array('filterOut2' => array('T_NS_SEPARATOR')),
                                   -1 => array('atom'       => $operands), 
                                    0 => array('token'      => Staticmethodcall::$operators),
                                    1 => array('token'      => 'T_OPEN_CURLY'),
                                    2 => array('atom'       => 'yes'),
                                    3 => array('token'      => 'T_CLOSE_CURLY'),
                                    4 => array('token'      => 'T_OPEN_PARENTHESIS'),
                                    5 => array('atom'       => array('Arguments', 'Void')),
                                    6 => array('token'      => 'T_CLOSE_PARENTHESIS'),
                                 );

        $this->actions = array('to_specialmethodcall' => true,
                               'makeSequence'    => 'it',
                               'atom'            => 'Staticmethodcall',
                               'cleanIndex'      => true);
        $this->checkAuto(); 

        // normal call : Class::Method();
        $this->conditions = array( -2 => array('filterOut2' => array('T_NS_SEPARATOR')),
                                   -1 => array('atom' => $operands), 
                                    0 => array('token' => Staticmethodcall::$operators),
                                    1 => array('atom' => array('Functioncall', 'Methodcall')),
                                 );
        
        $this->actions = array('makeEdge'     => array( -1 => 'CLASS',
                                                         1 => 'METHOD'),
                               'makeSequence' => 'it',
                               'atom'         => 'Staticmethodcall',
                               'cleanIndex'   => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

methode = fullcode.out("METHOD").next().getProperty('fullcode');
if (fullcode.out("METHOD").next().getProperty('block') == true) {
    methode = "{" + methode + "}";
}

fullcode.setProperty('fullcode', fullcode.out("CLASS").next().getProperty('fullcode') + "::" + methode); 

GREMLIN;
    }
}

?>