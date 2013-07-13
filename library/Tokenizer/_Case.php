<?php

namespace Tokenizer;

class _Case extends TokenAuto {
    function _check() {
        $this->conditions = array(-2 => array('token' => 'T_CASE',
                                             'atom' => 'none'),
                                  -1 => array('atom' => 'yes'),
                                  0 => array('token' => 'T_COLON'),
                                  1 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT')),
        );
        
        $this->actions = array('addEdge'    => array(0 => array('Block' => 'CODE')));
        $r = $this->checkAuto();

        $this->conditions = array(-2 => array('token' => 'T_CASE',
                                              'atom' => 'none'),
                                  -1 => array('atom' => 'yes'),
                                   0 => array('token' => 'T_COLON'),
                                   1 => array('atom' => array('Postplusplus', 'Assignation', 'Break', 'Return', )), 
        );
        
        $this->actions = array('createSequenceWithNext'    => true);
        $r = $this->checkAuto();

        $this->conditions = array(-3 => array('token' => 'T_CASE',
                                              'atom' => 'none'),
                                  -2 => array('atom' => 'yes'),
                                  -1 => array('token' => 'T_COLON'),
                                   0 => array('atom' => 'Sequence'), 
                                   1 => array('atom' => 'Sequence'), 
        );
        $this->actions = array( 'transform' => array(1 => 'ELEMENT'), 
                                'mergeNext' => array('Sequence' => 'ELEMENT'));
        $r = $this->checkAuto();

        $this->conditions = array(0 => array('token' => 'T_CASE',
                                              'atom' => 'none'),
                                  1 => array('atom' => 'yes'),
                                  2 => array('token' => 'T_COLON'),
                                  3 => array('atom' => array('Block')), 
        );
        
        $this->actions = array('transform'    => array( 1 => 'CASE',
                                                        2 => 'DROP',
                                                        3 => 'CODE',),
                                'atom' => 'Case' );
        $r = $this->checkAuto();

        return $r;
    }
}

?>