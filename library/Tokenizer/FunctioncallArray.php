<?php

namespace Tokenizer;

class FunctioncallArray extends TokenAuto {
    static public $operators = array('S_ARRAY');
    static public $atom = 'Functioncall';

    public function _check() {
        // $x[3]()
        $this->conditions = array(   0 => array('atom'  => 'Array'),
                                     1 => array('atom'  => 'none',
                                                'token' => 'T_OPEN_PARENTHESIS' ),
                                     2 => array('atom'  =>  array('Arguments', 'Void')),
                                     3 => array('atom'  => 'none',
                                                'token' => 'T_CLOSE_PARENTHESIS'),
        );

        $this->actions = array('transform'    => array(1 => 'DROP',
                                                       2 => 'ARGUMENTS',
                                                       3 => 'DROP'),
                               'atom'         => 'Functioncall',
                               'makeSequence' => 'it'
                               );
        $this->checkAuto();

        // $x[3]() 
        $this->conditions = array(   0 => array('atom'     => 'Array'),
                                     1 => array('notToken' => array('T_OPEN_PARENTHESIS', 'T_OPEN_BRACKET')));

        $this->actions = array('cleanIndex' => 'yes');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.setProperty('fullcode', fullcode.out('VARIABLE').next().getProperty('fullcode') + "[" + 
                                 fullcode.out('INDEX').next().getProperty('fullcode')    + "]" + 
                                 fullcode.out("ARGUMENTS").next().getProperty('fullcode'));

// count the number of arguments
// filter out void ? 
fullcode.setProperty("count", fullcode.out("ARGUMENTS").out("ARGUMENT").count()); 

GREMLIN;
    }


}
?>