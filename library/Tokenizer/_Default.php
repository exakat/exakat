<?php

namespace Tokenizer;

class _Default extends TokenAuto {
    function _check() {
    // case where default is empty
        $this->conditions = array(-1 => array('token' => 'T_DEFAULT',
                                             'atom' => 'none'),
                                  0 => array('token' => 'T_COLON'),
                                  1 => array('token' => array('T_CLOSE_CURLY', 'T_CASE', 'T_DEFAULT')),
        );
        
        $this->actions = array('addEdge'    => array(0 => array('Block' => 'CODE')));
        $r = $this->checkAuto();

        $this->conditions = array(-1 => array('token' => 'T_DEFAULT',
                                              'atom' => 'none'),
                                   0 => array('token' => 'T_COLON'),
                                   1 => array('atom' => array('Postplusplus', 'Assignation', 'Break', 'Ifthen' )), 
        );
        
        $this->actions = array('createSequenceWithNext'    => true);
        $r = $this->checkAuto();

        $this->conditions = array(-2 => array('token' => 'T_DEFAULT',
                                              'atom' => 'none'),
                                  -1 => array('token' => 'T_COLON'),
                                   0 => array('atom' => 'Sequence'), 
                                   1 => array('atom' => 'Sequence'), 
        );
        $this->actions = array( 'transform' => array(1 => 'ELEMENT'), 
                                'mergeNext' => array('Sequence' => 'ELEMENT'));
        $r = $this->checkAuto();

        $this->conditions = array(0 => array('token' => 'T_DEFAULT',
                                              'atom' => 'none'),
                                  1 => array('token' => 'T_COLON'),
                                  2 => array('atom' => array('Block')), // everything except block or Sequence is not a Block, as selected before
        );
        
        $this->actions = array('transform'    => array( 1 => 'DROP',
                                                        2 => 'CODE',),
                                'atom' => 'Default' );
        $r = $this->checkAuto();

        return $r;
    }
}

?>