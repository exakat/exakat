<?php

namespace Tokenizer;

class _While extends TokenAuto {
    static public $operators = array('T_WHILE');
    static public $atom = 'While';

    public function _check() {
         // While( condition ) ;
        $this->conditions = array( 0 => array('token'     => _While::$operators,
                                              'dowhile'   => 'false'),
                                   1 => array('token'     => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'      => 'yes'),
                                   3 => array('token'     => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token'     => array('T_SEMICOLON', 'T_CLOSE_TAG'), 
                                              'atom'      => 'none'),
        );
        
        $this->actions = array('addEdge'     => array(4 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
        $this->checkAuto();        

         //  syntax   While() $x++; 
        $this->conditions = array( 0 => array('token'      => _While::$operators,
                                              'dowhile'   => 'false'),
                                   1 => array('token'      => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'       => 'yes'),
                                   3 => array('token'      => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('atom'       => 'yes', 
                                              'notAtom'    => 'Sequence'),
                                   5 => array('filterOut2' => Token::$instruction_ending),
        );
        
        $this->actions = array('while_to_block' => true,
                               'keepIndexed'    => true
                               );
        $this->checkAuto();      
        
         //  While( ) {}
       $this->conditions = array( 0 => array('token'   => _While::$operators,
                                             'dowhile' => 'false'),
                                  1 => array('token'   => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'    => 'yes'),
                                  3 => array('token'   => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('atom'    => array('Sequence', 'Void')),
        );
        
        $this->actions = array('transform'    => array(  1 => 'DROP',
                                                         2 => 'CONDITION',
                                                         3 => 'DROP',
                                                         4 => 'LOOP',      ),
                               'makeSequence' => 'it',
                               'atom'         => 'While',
                               'cleanIndex'   => true);
        $this->checkAuto();
        
        // alternative syntax While( ) : endwhile
        $this->conditions = array(0 => array('token'   => _While::$operators,
                                             'dowhile' => 'false'),
                                  1 => array('token'   => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'    => 'yes'),
                                  3 => array('token'   => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token'   => 'T_COLON'),
                                  5 => array('atom'    => 'yes'),
                                  6 => array('token'   => 'T_ENDWHILE'),
        );
        
        $this->actions = array('transform'    => array(  1 => 'DROP',
                                                         2 => 'CONDITION',
                                                         3 => 'DROP',
                                                         4 => 'DROP',
                                                         5 => 'LOOP',
                                                         6 => 'DROP',
                                                        ),
                               'makeSequence' => 'it',
                               'atom'         => 'While',
                               'cleanIndex'   => true);
        $this->checkAuto();
        
        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = "while " + fullcode.out("CONDITION").next().fullcode + " " + fullcode.out("LOOP").next().fullcode;
GREMLIN;

    }

}

?>