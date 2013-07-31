<?php

namespace Tokenizer;

class _Try extends TokenAuto {
    static public $operators = array('T_TRY');

    function _check() {
        $this->conditions = array(0 => array('token' => _Try::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => 'Block'), 
                                  2 => array('atom' => 'Catch'),
                                  );
        
        $this->actions = array('transform'  => array( 1 => 'CODE',
                                                      2 => 'CATCH', 
                                                        ),
                               'atom'       => 'Try',
                               'keepIndexed' => true);
        $this->checkAuto();

        $this->conditions = array(0 => array('atom'  => 'yes', 
                                             'token' => _Try::$operators),
                                  1 => array('atom'  => 'Catch')
                                  );
        $this->actions = array('transform'    => array( 1 => 'CATCH' ),
                               'keepIndexed' => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }
}

?>