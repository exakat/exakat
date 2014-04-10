<?php

namespace Tokenizer;

class _While extends TokenAuto {
    static public $operators = array('T_WHILE');

    public function _check() {

         //  While( condition ) ;
         // T_SEMICOLON here will prevent while to be create too hastily, and give a chance to do...while.
        $this->conditions = array(-1 => array('filterOut' => array('T_CLOSE_CURLY', 'T_SEMICOLON'),
                                              'notAtom' => array("Sequence", 'Ifthen', 'Foreach', 'For', 'Switch')),
                                   0 => array('token' => _While::$operators),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG'), 
                                              'atom' => 'none'),
        );
        
        $this->actions = array('addEdge'     => array(4 => array('Void' => 'LEVEL')),
                               'keepIndexed'          => true,
                               'cleanIndex'           => true);
//        $this->checkAuto();        

         //  (instruction + ;) While( condition ) ;
        $this->conditions = array(-3 => array('notToken'  => 'T_DO'), 
                                  -2 => array('atom'  => 'yes'), 
                                  -1 => array('token' => 'T_SEMICOLON',
                                              'atom'  => 'none'), 
                                   0 => array('token' => _While::$operators),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG'), 
                                              'atom' => 'none'),
        );
        
        $this->actions = array('addEdge'     => array(4 => array('Void' => 'LEVEL')),
                               'keepIndexed'          => true,
                               'cleanIndex'           => true);
//        $this->checkAuto();        

         //  (Instruction no ;) While( condition ) ;
        $this->conditions = array(-2 => array('notToken'  => array('T_DO', 'T_ELSE', 'T_ELSEIF')), 
                                  -1 => array('atom'  => 'yes',
                                              'notToken' => array('T_ELSE', 'T_ELSEIF', 'T_OPEN_CURLY')), 
                                   0 => array('token' => _While::$operators),
                                   1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'  => 'yes'),
                                   3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token' => array('T_SEMICOLON', 'T_CLOSE_TAG'), 
                                              'atom'  => 'none'),
        );
        
        $this->actions = array('addEdge'     => array(4 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
//        $this->checkAuto();        
        
         // { lone block } While( condition ) ;
        $this->conditions = array(-2 => array('filterOut' => array("T_DO" , 'T_ELSE', 'T_ELSEIF')),
                                  -1 => array('atom'      => "yes"),
                                   0 => array('token'     => _While::$operators),
                                   1 => array('token'     => 'T_OPEN_PARENTHESIS'),
                                   2 => array('atom'      => 'yes'),
                                   3 => array('token'     => 'T_CLOSE_PARENTHESIS'),
                                   4 => array('token'     => array('T_SEMICOLON', 'T_CLOSE_TAG'), 
                                              'atom'      => 'none'),
        );
        
        $this->actions = array('addEdge'     => array(4 => array('Void' => 'LEVEL')),
                               'keepIndexed' => true,
                               'cleanIndex'  => true);
//        $this->checkAuto();        

         //  syntax   While() $x++; 
        $this->conditions = array(-1 => array('filterOut2' => array('T_CLOSE_CURLY')),
                                   0 => array('token'      => _While::$operators),
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
        
         //  syntax   While( ) {}
       $this->conditions = array(0 => array('token'  => _While::$operators),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'yes'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('atom'  => array('Sequence', 'Void')),
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
        $this->conditions = array(0 => array('token' => _While::$operators),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'yes'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token' => 'T_COLON'),
                                  5 => array('atom'  => 'yes'),
                                  6 => array('token'  => 'T_ENDWHILE'),
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