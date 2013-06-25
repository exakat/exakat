<?php

namespace Tokenizer;

class Assignation extends TokenAuto {
    function _check() {

        $operands = array('Integer', 'Multiplication', 'Addition', 'Not',
                          'Array', 'Float', 'Concatenation', 'Property',
                          'Parenthesis', 'Noscream', 'Ternary', 'New', 'String',
                          'Constant', 'Functioncall', 'Staticproperty', 'Staticconstant', 'Property',
                          'Heredoc',  );
        
        $this->conditions = array(//-2 => array('filterOut' => array( 'T_VAR')),
                                  -1 => array('atom' => array('Variable', 'Array', 'Property', 'Staticproperty')),
                                   0 => array('code' => array('=', '+=','.=',),
                                             'atom' => 'none'),
                                   1 => array('atom' => $operands),
                                  
        );
        
        $this->actions = array('makeEdge'    => array( '1' => 'RIGHT',
                                                      '-1' => 'LEFT'),
                               'atom'       => 'Assignation');

        return $this->checkAuto();
    } 
    
    function reserve() {
        Token::$reserved[] = 'T_EQUAL';
        Token::$reserved[] = 'T_PLUS_EQUAL';

        return true;
    }
    
}
?>