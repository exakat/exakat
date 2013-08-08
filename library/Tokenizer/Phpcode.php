<?php

namespace Tokenizer;

class Phpcode extends TokenAuto {
    static public $operators = array('T_OPEN_TAG');

    function _check() {
        $this->conditions = array(0 => array('token' => array('T_OPEN_TAG'),
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_CLOSE_TAG'),
        );
        
        $this->actions = array('transform'   => array( 1 => 'CODE',
                                                       2 => 'DROP'),
                               'atom'       => 'Phpcode',
                               'cleanIndex' => true);
        $this->checkAuto();

// <?php echo 3 ( No closing tag)
        $this->conditions = array(0 => array('token' => array('T_OPEN_TAG'),
                                             'atom'  => 'none'),
                                  1 => array('atom'  => 'yes'),
                                  2 => array('token' => 'T_END'),
        );
        
        $this->actions = array('transform'  => array( 1 => 'CODE'),
                               'atom'       => 'Phpcode');
        $this->checkAuto();

// <?php ? > (empty script 
        $this->conditions = array(0 => array('token' => array('T_OPEN_TAG'),
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_CLOSE_TAG'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        0 => 'DROP'), // Yes, 0 must be last.
                               'atom'       => 'Phpcode');
        $this->checkAuto();
        
        return $this->checkRemaining();
    }
}

?>