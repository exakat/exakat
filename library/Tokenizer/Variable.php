<?php

namespace Tokenizer;

class Variable extends TokenAuto {
    static public $operators = array('T_DOLLAR_OPEN_CURLY_BRACES', 'T_CURLY_OPEN');
    
    public function _check() {
        // "  {$variable}  " or " ${x} "
        $this->conditions = array(0 => array('token' => Variable::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => String::$allowed_classes,),
                                  2 => array('token' => 'T_CLOSE_CURLY'),
        );
        
        $this->actions = array( 'to_variable' => 1,
                                'cleanIndex' => true);
        $this->checkAuto();
        
        // todo find a way to process those remainings atom that may be found in those {} 
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return '
        
it.fullcode = it.code; 
if (it.reference == "true") {
    it.fullcode = "&" + it.fullcode;
}

x = it;
it.has("token", "T_STRING_VARNAME").each{ x.fullcode = "\\$" + it.code; }
it.has("token", "T_DOLLAR").filter{   it.out("NAME").next().atom in ["Variable", "Identifier"] }.out("NAME").each{ x.fullcode = "\\$" + it.fullcode; }
it.has("token", "T_DOLLAR").filter{ !(it.out("NAME").next().atom in ["Variable", "Identifier"])}.out("NAME").each{ x.fullcode = "\\${" + it.fullcode + "}"; }

it.has("token", "T_DOLLAR_OPEN_CURLY_BRACES").out("NAME").has("atom", "Identifier").each{ x.fullcode = "\\${" + it.fullcode + "}"; }
it.has("token", "T_CURLY_OPEN").out("NAME").each{ x.fullcode = it.fullcode; }

';
    }
}

?>