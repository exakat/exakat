<?php

namespace Tokenizer;

class IfthenElse extends TokenAuto {
    public static $operators = array('T_ELSE');
    
    public function _check() {
 
    // @doc else : endif (empty )
        $this->conditions = array( 0 => array('token' => 'T_ELSE'),
                                   1 => array('token' => 'T_COLON'),
                                   2 => array('token' => 'T_ENDIF'),
        );
        
        $this->actions = array('insertVoid'  => 1,
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto(); 

        return $this->checkRemaining();
    }
}

?>