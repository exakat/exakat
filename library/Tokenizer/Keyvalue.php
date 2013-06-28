<?php

namespace Tokenizer;

class Keyvalue extends TokenAuto {
    function _check() {
    
    // @doc if then else
        $this->conditions = array(-2 => array('filterOut' => array( 'T_NS_SEPARATOR')), 
                                  -1 => array('atom' => 'yes'),
                                   0 => array('token' => 'T_DOUBLE_ARROW'),
                                   1 => array('atom' => 'yes'),
                                   2 => array('filterOut' => array_merge( Assignation::$operators, 
                                            array('T_OPEN_BRACKET', 'T_OBJECT_OPERATOR', 'T_INC', 'T_DEC', 'T_NS_SEPARATOR',
                                                  'T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', ))),
        );
        
        /*
        '[','->','++','--','=','.=',
                                                '*=','+=','-=','/=','%=',
                                                '>>=','&=','^=','>>>=', '|=',
                                                '<<=','>>=','?','(','{'
                                                */
        
        $this->actions = array('transform'    => array('-1' => 'KEY',
                                                       '1' => 'VALUE'
                                                      ),
                               'atom'       => 'Keyvalue',
                               );

        $r = $this->checkAuto(); 

        return $r;
    }
}

?>
