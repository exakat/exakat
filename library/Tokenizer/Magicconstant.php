<?php

namespace Tokenizer;

class Magicconstant extends TokenAuto {
    static public $operators = array('T_CLASS_C','T_FUNC_C', 'T_DIR', 'T_FILE', 'T_LINE','T_METHOD_C', 'T_NS_C');
    static public $atom = 'Magicconstant';

    public function _check() {

        $this->conditions = array( 0 => array('token' => Magicconstant::$operators,
                                              'atom'  => 'none'));
        $this->actions = array('atom'       => 'Magicconstant');
        
        return $this->checkAuto();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = fullcode.code; 

GREMLIN;
    }
}

?>