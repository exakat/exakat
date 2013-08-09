<?php

namespace Tokenizer;

class Arguments extends TokenAuto {
    static public $operators = array('T_COMMA');

    static public $operands_wa = array('Addition', 'Multiplication', 'Sequence', 'String', 
                                       'Integer', 'Float', 'Not', 'Variable', 'Array', 'Concatenation', 'Sign',
                                       'Functioncall', 'Boolean', 'Comparison', 'Parenthesis', 'Constant', 'Array',
                                       'Magicconstant', 'Ternary', 'Assignation', 'Logical', 'Keyvalue', 'Void', 
                                       'Property', 'Staticconstant', 'Staticproperty', 'Nsname', 'Methodcall', 'Staticmethodcall',
                                       'Reference', 'Cast', 'Postplusplus', 'Preplusplus', 'Typehint', 'Bitshift', 'Noscream',
                                       'Clone', 'New' );

    function _check() {
        // Argument next to ( 
        $this->conditions = array(-1 => array('token' => 'T_OPEN_PARENTHESIS',
                                              'atom' => 'none'),
                                   0 => array('token' => Arguments::$operators,
                                              'atom'  => 'none'),
        );
        $this->actions = array('addEdge'     => array(-1 => array('Void' => 'ARGUMENT')),
                               'keepIndexed' => true);
        $this->checkAuto();
        
        // Argument next to ) 
        $this->conditions = array( 0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
        );
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'ARGUMENT')),
                               'keepIndexed' => true);
        $this->checkAuto();

        // @note End of )
        $this->conditions = array( 0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_COMMA',
                                              'atom'  => 'none'),
        );
        $this->actions = array('addEdge'   => array(0 => array('Void' => 'ARGUMENT')),
                               'keepIndexed' => true);
        $this->checkAuto();

        $operands = Arguments::$operands_wa;
        $operands[] = 'Arguments';
        
        // @note arguments separated by ,
        $this->conditions = array(-2 => array('token' => array_merge(array('T_COMMA', 'T_OPEN_PARENTHESIS', 'T_ECHO', 'T_GLOBAL', 'T_IMPLEMENTS', 'T_EXTENDS', 'T_VAR', 'T_SEMICOLON', 'T_STATIC', 'T_DECLARE' ), 
                                                                     _Ppp::$operators)),
                                  -1 => array('atom' => $operands ),
                                   0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => $operands),
                                   2 => array('token' => array('T_COMMA', 'T_CLOSE_PARENTHESIS', 'T_SEMICOLON')),
                                 );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'       => array( 1 => '2',
                                                      -1 => '1'),
                               'mergeNext'   => array('Arguments' => 'ARGUMENT'), 
                               'atom'        => 'Arguments',
                               'cleanIndex' => true
                               );
        $this->checkAuto();

        // @note arguments separated by ,
        $this->conditions = array(-1 => array('atom' => 'String' ),
                                   0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('atom' => 'String'),
                                   2 => array('token' => 'T_OPEN_CURLY'),
                                 );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'       => array( 1 => '2',
                                                      -1 => '1'),
                               'mergeNext'   => array('Arguments' => 'ARGUMENT'), 
                               'atom'        => 'Arguments',
                               'cleanIndex' => true
                               );
        $this->checkAuto();


        // @note implements a,b (two only)
        $this->conditions = array(-2 => array('token' => 'T_IMPLEMENTS' ),
                                  -1 => array('token' => 'T_STRING'),
                                   0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_STRING')
                            );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'    => array( 1 => '2',
                                                   -1 => '1'),
                               'mergeNext'  => array('Arguments' => 'ARGUMENT'), 
                               'atom'       => 'Arguments',
                               'cleanIndex' => true
                               );
        $this->checkAuto();

        // @note implements a,b,c (three or more)
        $this->conditions = array(-2 => array('token' => 'T_IMPLEMENTS' ),
                                  -1 => array('atom' => 'Arguments'),
                                   0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_STRING')
                            );
        
        $this->actions = array('makeEdge'    => array( 1 => 'ARGUMENT',
                                                      -1 => 'ARGUMENT'
                                                      ),
                               'order'    => array( 1 => '2',
                                                   -1 => '1'),
                               'mergeNext'  => array('Arguments' => 'ARGUMENT'), 
                               'atom'       => 'Arguments',
                               'cleanIndex' => true
                               );
        $this->checkAuto();

        // @note End of )
        $this->conditions = array(-2 => array('filterOut' => array("T_NS_SEPARATOR")),
                                  -1 => array('atom' => $operands),
                                   0 => array('token' => Arguments::$operators,
                                              'atom' => 'none'),
                                   1 => array('token' => 'T_CLOSE_PARENTHESIS',
                                              'atom'  => 'none'),
        );
        
        $this->actions = array('makeEdge'    => array(-1 => 'ARGUMENT'
                                                      ),
                               'order'    => array('-1' => '1'),
                               'atom'       => 'Arguments',
                               );
        $this->checkAuto();

        return $this->checkRemaining();
    }
}
?>