<?php

namespace Tokenizer;

class Arrayappend extends TokenAuto {
    static public $operators = array('T_OPEN_BRACKET');
    static public $atom = 'Arrayappend';
    
    public function _check() {
        $this->conditions = array(-2 => array('filterOut' => array('T_DOUBLE_COLON', 'T_OBJECT_OPERATOR')), 
                                  -1 => array('atom' => array('Variable', 'Property', 'Staticproperty', 'Array', 'Arrayappend')),
                                   0 => array('token' => Arrayappend::$operators),
                                   1 => array('token' => 'T_CLOSE_BRACKET'),
        );
        
        $this->actions = array('transform'  => array(  -1 => 'VARIABLE',
                                                        1 => 'DROP'),
                               'atom'       => 'Arrayappend',
                               'cleanIndex' => true);
        $this->checkAuto();
        
        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode',  fullcode.out("VARIABLE").next().getProperty('fullcode') + "[]");

GREMLIN;
    }
}

?>