<?php

namespace Tokenizer;

class _Goto extends TokenAuto {
    static public $operators = array('T_GOTO');
    
    function _check() {
        $this->conditions = array(0 => array('token' => _Goto::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'String')
                                  );
        
        $this->actions = array('transform'  => array(1 => 'LABEL'),
                               'atom'       => 'Goto',
                               'cleanIndex' => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>