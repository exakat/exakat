<?php

namespace Tokenizer;

class _Class extends TokenAuto {
    function _check() {
    
    // class x {}
        $this->conditions = array( 0 => array('token' => 'T_CLASS'),
                                   1 => array('atom' => 'String')
                                 );
        
        $this->actions = array('transform'   => array(   1 => 'NAME'),
                               'atom'       => 'Class_tmp',
                               );

        $r = $this->checkAuto(); 

    // class x extends y {}
        $this->conditions = array( 0 => array('atom' => 'Class_tmp'),
                                   1 => array('token' => 'T_EXTENDS'),
                                   2 => array('atom' => 'String')
                                 );
        
        $this->actions = array('transform'   => array( 1 => 'DROP',
                                                       2 => 'EXTENDS')
                               );
        $r = $this->checkAuto(); 

    // class x implements a {}
        $this->conditions = array( 0 => array('atom' => 'Class_tmp'),
                                   1 => array('token' => 'T_IMPLEMENTS'),
                                   2 => array('atom' => 'String'),
                                   3 => array('filterOut' => array('T_COMMA')),
                                 );
        
        $this->actions = array('transform'   => array( 1 => 'DROP',
                                                       2 => 'IMPLEMENTS')
                               );
        $r = $this->checkAuto(); 

    // class x implements a,b,c {}
        $this->conditions = array( 0 => array('atom' => 'Class_tmp'),
                                   1 => array('token' => 'T_IMPLEMENTS'),
                                   2 => array('atom' => 'Arguments'),
                                   3 => array('filterOut' => array('T_COMMA')),
                                 );
        
        $this->actions = array('transform'   => array( 1 => 'DROP',
                                                       2 => 'TO_IMPLEMENTS')
                               );
        $r = $this->checkAuto(); 

    // class x {}
        $this->conditions = array( 0 => array('atom' => 'Class_tmp'),
                                   1 => array('token' => 'T_OPEN_CURLY'),
                                   2 => array('token' => 'T_CLOSE_CURLY'),
                                 );
        
        $this->actions = array('transform'   => array(1 => 'BLOCK',
                                                      2 => 'DROP',),
                               'atom'       => 'Class',
                                );

        $r = $this->checkAuto(); 

    // class x { // some real code}
        $this->conditions = array( 0 => array('atom' => 'Class_tmp'),
                                   1 => array('atom' => 'Block')
                                 );
        
        $this->actions = array('transform'   => array(1 => 'BLOCK'),
                               'atom'       => 'Class',
                                );

        $r = $this->checkAuto(); 

        return $r;
    }
}
?>