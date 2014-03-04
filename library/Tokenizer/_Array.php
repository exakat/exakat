<?php

namespace Tokenizer;

class _Array extends TokenAuto {
    static public $operators = array('T_OPEN_BRACKET', 'T_OPEN_CURLY');
    
    public function _check() {
        // $x[3]
        $this->conditions = array( -1 => array('atom' => array('Variable', 'Array', 'Property', 'Staticproperty', 'Arrayappend', )),
                                    0 => array('token' => _Array::$operators),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => array('T_CLOSE_BRACKET', 'T_CLOSE_CURLY')),
                                    3 => array('atom'  => 'none',
                                               'token' => 'T_OPEN_PARENTHESIS' ),
                                    
                                 );
        
        $this->actions = array('transform'    => array(  -1 => 'VARIABLE', 
                                                          1 => 'INDEX',
                                                          2 => 'DROP'),
                               'atom'         => 'Array',
                               'cleanIndex'   => true,
                               'add_to_index' => array('S_ARRAY' => 'S_ARRAY'),
                               );
        $this->checkAuto(); 

        // $x[3]
        $this->conditions = array( -1 => array('atom' => array('Variable', 'Array', 'Property', 'Staticproperty', 'Arrayappend', )),
                                    0 => array('token' => _Array::$operators),
                                    1 => array('atom' => 'yes'),
                                    2 => array('token' => array('T_CLOSE_BRACKET', 'T_CLOSE_CURLY')),
                                 );
        
        $this->actions = array('transform'   => array(  -1 => 'VARIABLE', 
                                                         1 => 'INDEX',
                                                         2 => 'DROP'),
                               'atom'        => 'Array',
                               'cleanIndex'  => true,
                               'add_to_index' => array('S_ARRAY' => 'S_ARRAY'),
                               );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        return '
x = it;
it.out("NAME").each { x.fullcode = it.fullcode }

it.filter{ it.out("INDEX").count() == 1}.each{ x.fullcode = it.out("VARIABLE").next().fullcode + "[" + it.out("INDEX").next().fullcode + "]"; }';
    }
}

?>