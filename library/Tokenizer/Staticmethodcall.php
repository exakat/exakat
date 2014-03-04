<?php

namespace Tokenizer;

class Staticmethodcall extends TokenAuto {
    static public $operators = array('T_DOUBLE_COLON');

    public function _check() {
        $operands = array('Constant', 'Identifier', 'Variable', 'Array', 'Nsname', 'Static', 'Nsname',);

        $this->conditions = array( -2 => array('filterOut2' => array('T_NS_SEPARATOR')),
                                   -1 => array('atom' => $operands), 
                                    0 => array('token' => Staticmethodcall::$operators),
                                    1 => array('atom' => array('Functioncall', 'Methodcall')),
                                 );
        
        $this->actions = array('makeEdge'   => array( -1 => 'CLASS',
                                                       1 => 'METHOD'),
                               'atom'       => 'Staticmethodcall',
                               'cleanIndex' => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        return 'it.fullcode = it.out("CLASS").next().fullcode + "::" + it.out("METHOD").next().fullcode; ';
    }
}

?>