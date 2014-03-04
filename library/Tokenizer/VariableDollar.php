<?php

namespace Tokenizer;

class VariableDollar extends TokenAuto {
    static public $operators = array('T_DOLLAR');
    
    public function _check() {
        // $x or $$x or $$$
        $this->conditions = array(0 => array('token' => VariableDollar::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Variable'),
                                  2 => array('filterOut' => array('T_OPEN_BRACKET')),
        );
        
        $this->actions = array( 'transform'  => array(1 => 'NAME'),
                                'atom'       => 'Variable',
                                'cleanIndex' => true);
        $this->checkAuto();

        // ${x}
        $this->conditions = array(0 => array('token' => VariableDollar::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_OPEN_CURLY'),
                                  2 => array('atom' => 'yes'),
                                  3 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'transform'  => array(1 => 'DROP',
                                                      2 => 'NAME',
                                                      3 => 'DROP'),
                                'atom'       => 'Variable',
                                'cleanIndex' => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

name = fullcode.out('NAME').next();
if (name.atom in ['Variable', 'Identifier'] ) {
    fullcode.fullcode = "\\\$" + name.fullcode; 
} else {
    fullcode.fullcode = "\\\${" + name.fullcode + "}"; 
}

GREMLIN;

    }
}
?>