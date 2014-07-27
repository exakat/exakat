<?php

namespace Tokenizer;

class _Array extends TokenAuto {
    static public $operators = array('T_OPEN_BRACKET', 'T_OPEN_CURLY');
    static public $atom = 'Array';
    static public $allowed_object = array('Variable', 'Array', 'Property', 'Staticproperty', 'Arrayappend', 'Functioncall', 'Methodcall', 'Staticmethodcall');
    
    public function _check() {
        // $x[3] (and keep the indexation for doing it again, or with FunctioncallArray);
        $this->conditions = array( -1 => array('atom'  => _Array::$allowed_object),
                                    0 => array('token' => _Array::$operators),
                                    1 => array('atom'  => 'yes'),
                                    2 => array('token' => array('T_CLOSE_BRACKET', 'T_CLOSE_CURLY')),
                                    3 => array('token' => array('T_OPEN_PARENTHESIS','T_OPEN_BRACKET', 'T_OPEN_CURLY')),
                                 );
        
        $this->actions = array('transform'    => array(  -1 => 'VARIABLE', 
                                                          1 => 'INDEX',
                                                          2 => 'DROP'),
                               'atom'         => 'Array',
                               'cleanIndex'   => true,
                               'add_to_index' => array('S_ARRAY' => 'S_ARRAY')
                               );
        $this->checkAuto(); 

        // $x[3] (and stop the indexation
        $this->conditions = array( -1 => array('atom'  => _Array::$allowed_object),
                                    0 => array('token' => _Array::$operators),
                                    1 => array('atom'  => 'yes'),
                                    2 => array('token' => array('T_CLOSE_BRACKET', 'T_CLOSE_CURLY')),
                                    3 => array('notToken' => array('T_OPEN_PARENTHESIS','T_OPEN_BRACKET', 'T_OPEN_CURLY')),
                                 );
        
        $this->actions = array('transform'    => array(  -1 => 'VARIABLE', 
                                                          1 => 'INDEX',
                                                          2 => 'DROP'),
                               'atom'         => 'Array',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               );
        $this->checkAuto(); 

        // $x[3] // will loop for each dimension
        $this->conditions = array( -1 => array('atom'  => _Array::$allowed_object),
                                    0 => array('token' => _Array::$operators),
                                    1 => array('atom'  => 'yes'),
                                    2 => array('token' => array('T_CLOSE_BRACKET', 'T_CLOSE_CURLY')),
                                    3 => array('notToken' => array('T_OPEN_PARENTHESIS', 'T_OPEN_BRACKET', 'T_OPEN_CURLY')),
                                 );
        
        $this->actions = array('transform'    => array(  -1 => 'VARIABLE', 
                                                          1 => 'INDEX',
                                                          2 => 'DROP'),
                               'atom'         => 'Array',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it',
                               );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.out("NAME").each { fullcode.setProperty('fullcode', fullcode.getProperty('fullcode')); }

fullcode.filter{ it.out("INDEX").count() == 1}.each{ fullcode.setProperty('fullcode', it.out("VARIABLE").next().getProperty('fullcode') + "[" + it.out("INDEX").next().getProperty('fullcode') + "]"); }

GREMLIN;
    }
}

?>