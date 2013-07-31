<?php

namespace Tokenizer;

class _Foreach extends TokenAuto {
    static public $operators = array('T_FOREACH');

    function _check() {
    // @doc foreach($x as $y) { code }
        $this->conditions = array( 0 => array('token' => _Foreach::$operators,
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom' => array('Variable', 'Array', 'Property', 'Staticproperty', 'Functioncall', 
                                                              'Staticmethodcall', 'Methodcall','Cast', 'Parenthesis', 'Ternary', 
                                                              'Noscream', 'Not', 'Assignation', )),
                                   3 => array('token' => 'T_AS'),
                                   4 => array('atom' => array('Variable', 'Keyvalue', 'Array', 'Staticproperty', 'Property', 'Reference' )),
                                   5 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   6 => array('atom' => 'Block'),
        );
        
        $this->actions = array('transform'    => array('1' => 'DROP',
                                                       '2' => 'SOURCE',    
                                                       '3' => 'DROP',
                                                       '4' => 'VALUE',
                                                       '5' => 'DROP',
                                                       '6' => 'LOOP',
                                                      ),
                               'atom'       => 'Foreach',
                               );
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>