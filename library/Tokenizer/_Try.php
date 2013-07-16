<?php

namespace Tokenizer;

class _Try extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_TRY',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Block'), 
                                  2 => array('atom' => 'Catch'),
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'CODE',
                                                        2 => 'CATCH', 
                                                        ),
        
                               'atom'       => 'Try');
                               
        $r = $this->checkAuto();

        $this->conditions = array(0 => array('atom' => 'Try'),
                                  1 => array('atom' => 'Catch')
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'CATCH' ));
                               
        $r = $this->checkAuto();

        return $r;
    }
}

?>