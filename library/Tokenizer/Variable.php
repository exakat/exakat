<?php

namespace Tokenizer;

class Variable extends TokenAuto {
    static public $operators = array('T_DOLLAR_OPEN_CURLY_BRACES', 'T_CURLY_OPEN');
    
    function _check() {

        // "  {$variable}  "
        $this->conditions = array(0 => array('token' => Variable::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => String::$allowed_classes,),
                                  2 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'transform' => array(1 => 'NAME',
                                                     2 => 'DROP'),
                                'atom'       => 1,
                                'cleanIndex' => true);
        $this->checkAuto();
        
        // todo find a way to process those remainings atom that may be found in those {} 
        
        return $this->checkRemaining();
    }

    function fullcode() {
        return 'it.fullcode = it.code; 
x = it;
it.out("NAME").each{ x.fullcode = it.fullcode; }
        ';
    }
}

?>