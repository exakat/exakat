<?php

namespace Tokenizer;

class _Goto extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_GOTO',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'String')
                                  );
        
        $this->actions = array('transform'   => array(1 => 'LABEL'),
                               'atom' => 'Goto');
                               
        $r = $this->checkAuto();

        return $r;
    }
}

?>