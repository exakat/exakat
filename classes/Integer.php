<?php

class Integer extends TokenAuto {
    function check() {
        $this->conditions = array(0 => array('token' => 'T_LNUMBER',
                                             'atom' => 'none')
                                  
        );
        
        $this->actions = array('atom'       => 'Integer');

        return $this->checkAuto();
    }
}

?>