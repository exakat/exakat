<?php

namespace Tokenizer;

class Phpcode extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_OPEN_TAG',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_CLOSE_TAG'),
        );
        
        $this->actions = array('transform'    => array( '1' => 'CODE',
                                                        '2' => 'DROP'),
                               'atom'       => 'Phpcode');
        $r = $this->checkAuto();

// <?php echo 3 ( No closing tag)
        $this->conditions = array(0 => array('token' => 'T_OPEN_TAG',
                                             'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_END'),
        );
        
        $this->actions = array('transform'    => array( '1' => 'CODE'),
                               'atom'       => 'Phpcode');
        $r = $this->checkAuto();

// <?php ? > (empty script 
        $this->conditions = array(0 => array('token' => 'T_OPEN_TAG',
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_CLOSE_TAG'),
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        0 => 'DROP'), // Yes, 0 must be last.
                               'atom'       => 'Phpcode');
        $r = $this->checkAuto();

// ? >A<?php 
        $this->conditions = array(-1 => array('token' => 'T_CLOSE_TAG',
                                              'atom' => 'none'),
                                   0 => array('atom' => 'yes'),
                                   1 => array('token' => 'T_OPEN_TAG',
                                              'atom' => 'none'),
        );
        
        $this->actions = array('transform'    => array( -1 => 'DROP',
                                                         1 => 'DROP',)
                              );
        $r = $this->checkAuto();
        
        return $r;
    }
}

?>