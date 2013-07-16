<?php

namespace Tokenizer;

class _Throw extends TokenAuto {
    function _check() {
        $this->conditions = array(0 => array('token' => 'T_THROW',
                                             'atom' => 'none'),
                                  1 => array('atom' => array('New', 'Variable', 'Functioncall', 'Property', 'Array', 'Methodcall', 
                                                              'Staticmethodcall', 'Staticproperty' ))
                                  );
        
        $this->actions = array('transform'    => array( 1 => 'THROW'),
                               'atom'       => 'Throw');
                               
        return $this->checkAuto();
    }
}

?>