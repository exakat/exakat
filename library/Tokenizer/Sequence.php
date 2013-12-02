<?php

namespace Tokenizer;

class Sequence extends TokenAuto {
    static public $operators = array('T_SEMICOLON');
    
    function _check() {
        $operands = array('Addition', 'Multiplication', 'String', 'Integer', 'Sequence', 
                          'Float', 'Not', 'Variable','Array','Concatenation', 'Sign',
                          'Functioncall', 'Constant', 'Parenthesis', 'Comparison', 'Assignation',
                          'Noscream', 'Staticproperty', 'Property', 'Ternary', 'New', 'Return',
                          'Instanceof', 'Magicconstant', 'Staticconstant', 'Methodcall', 'Logical',
                          'Var', 'Const', 'Ppp', 'Postplusplus', 'Preplusplus', 'Global', 'Nsname',
                          'Ifthen', 'Include', 'Function', 'Foreach', 'While', 'Arrayappend', 'Cast',
                          'Break', 'Goto', 'Label', 'Switch', 'Staticmethodcall',
                          'Static', 'Continue', 'Class', 'For', 'Throw', 'Try', 'Abstract', 'Final',
                          'Bitshift', 'Void', 'Dowhile', 'Clone', 'Declare', 'Halt', 'Interface', 'Block', 
                          'RawString', 'Namespace', 'Boolean', 'Use', 'ArrayNS', 
                           );
        
        $yield_operator = array('T_ECHO', 'T_PRINT', 'T_DOT', 'T_AT', 'T_OBJECT_OPERATOR', 'T_BANG',
                                'T_DOUBLE_COLON', 'T_COLON', 'T_NEW', 'T_INSTANCEOF', 'T_IF', 
                                'T_AND', 'T_QUOTE', 'T_DOLLAR', 'T_VAR', 'T_CONST', 'T_COMMA',
                                'T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC', 'T_INC', 'T_DEC', 'T_GLOBAL', 'T_NS_SEPARATOR',
                                'T_GOTO', 'T_STATIC', 'T_OPEN_PARENTHESIS', 'T_ELSE', 'T_ELSEIF', 'T_CLOSE_PARENTHESIS',
                                'T_THROW', 'T_CATCH', 'T_ABSTRACT', 'T_CASE', 'T_DEFAULT', 'T_CLONE', 'T_DECLARE',
                                'T_STRING', 'T_USE', 'T_AS', 'T_NAMESPACE', 'T_DO',
                                 );
                                 
        $yield_operator = array_merge($yield_operator, Assignation::$operators, Addition::$operators, Multiplication::$operators, 
                                      Comparison::$operators, Cast::$operators, Logical::$operators, Bitshift::$operators, 
                                      _Include::$operators );
        $next_operator = array_merge(array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_COMMA', 
                                           'T_CLOSE_PARENTHESIS', 'T_CATCH', 'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_ELSEIF', 'T_NS_SEPARATOR' ), 
                                     Assignation::$operators, Logical::$operators, Preplusplus::$operators, Postplusplus::$operators);
        
        // @note instructions separated by ; 
        $this->conditions = array(-2 => array('filterOut2' => $yield_operator, 
                                              'notAtom'    => 'Parenthesis'), 
                                  -1 => array('atom'       => $operands, 
                                              'notToken'   => 'T_ELSEIF' ),
                                   0 => array('token'      => Sequence::$operators,
                                              'atom'       => 'none'),
                                   1 => array('atom'       => $operands, 
                                              'notToken'   => 'T_ELSEIF'),
                                   2 => array('filterOut2' => $next_operator),
        );
        
        $this->actions = array('transform'   => array( 1 => 'ELEMENT',
                                                      -1 => 'ELEMENT'),
                               'order'      => array( 1 => 2,
                                                     -1 => 1 ),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               'cleanIndex' => true
                               );
        $this->checkAuto();

        // @note instructions separated by ; but ; is useless 
        $this->conditions = array(-1 => array('atom'     => $operands, 
                                              'notToken' => 'T_ELSEIF' ),
                                   0 => array('token'    => Sequence::$operators,
                                              'atom'     => 'none'),
                                   1 => array('token'    => array('T_ELSEIF', 'T_ELSE', 'T_ENDIF', 'T_ENDWHILE', 'T_ENDDECLARE')),
        );
        
        $this->actions = array('transform'   => array( 0 => 'DROP'));
        $this->checkAuto();

        // @note instructions separated by ; with a special case for alternative syntax
        $this->conditions = array(-4 => array('token' => array('T_QUESTION')), 
                                  -3 => array('token' => array('T_OPEN_PARENTHESIS', 'T_ELSE')), //'T_CLOSE_PARENTHESIS', 
                                  -2 => array('token' => 'T_COLON'), 
                                  -1 => array('atom'  => $operands, 'notToken' => 'T_ELSEIF' ),
                                   0 => array('token' => Sequence::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => $operands, 'notToken' => 'T_ELSEIF' ),
                                   2 => array('filterOut' => $next_operator),
        );
        
        $this->actions = array('makeEdge'   => array( 1 => 'ELEMENT',
                                                     -1 => 'ELEMENT'),
                               'order'      => array( 1 => 2,
                                                     -1 => 1 ),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               'cleanIndex' => true);
        $this->checkAuto();

        // @note instructions separated by ; with a special case for 'case'
        $this->conditions = array(-4 => array('token' => 'T_CASE', 
                                              'atom'  => 'none',),
                                  -3 => array('atom'  => 'yes'),
                                  -2 => array('token' => 'T_COLON',
                                              'atom'  => 'none', ), 
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => Sequence::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => $operands),
                                   2 => array('filterOut2' => $next_operator),
        );
        
        $this->actions = array('transform'    => array( 1 => 'ELEMENT',
                                                       -1 => 'ELEMENT'
                                                      ),
                               'order'    => array( 1 => 2,
                                                   -1 => 1 ),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               'cleanIndex' => true
                               );
        $this->checkAuto();

        // @note instructions separated by ; with a special case for 'default'
        $this->conditions = array(-3 => array('token' => 'T_DEFAULT', 
                                              'atom'  => 'none',),
                                  -2 => array('token' => 'T_COLON',
                                              'atom'  => 'none', ), 
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => Sequence::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => $operands),
                                   2 => array('filterOut2' => $next_operator),
        );
        
        $this->actions = array('transform'  => array( 1 => 'ELEMENT',
                                                     -1 => 'ELEMENT'),
                               'order'      => array( 1 => 2,
                                                     -1 => 1 ),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               'cleanIndex' => true
                               );
        $this->checkAuto();

        // @note instructions separated by ; with a special case for 'while'
        $this->conditions = array(-6 => array('token' => 'T_WHILE', ),
                                  -5 => array('token' => 'T_OPEN_PARENTHESIS', ),
                                  -4 => array('atom' => 'yes', ),
                                  -3 => array('token' => 'T_CLOSE_PARENTHESIS', ),
                                  -2 => array('token' => 'T_COLON',
                                              'atom'  => 'none', ), 
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => Sequence::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => $operands),
                                   2 => array('filterOut2' => $next_operator),
        );
        
        $this->actions = array('transform'  => array( 1 => 'ELEMENT',
                                                     -1 => 'ELEMENT'),
                               'order'      => array( 1 => 2,
                                                     -1 => 1 ),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               'cleanIndex' => true
                               );
        $this->checkAuto();
        // @note instructions separated by ; with a special case for 'foreach' and 'for'. 
        // @note this is not sufficient, but it seems to works pretty well and be enough.
        $this->conditions = array(-5 => array('token'  => array('T_SEMICOLON', 'T_AS')),
                                  -4 => array('atom'  => 'yes',),
                                  -3 => array('token'  => 'T_CLOSE_PARENTHESIS'),
                                  -2 => array('token' => 'T_COLON',
                                              'atom'  => 'none', ), 
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => Sequence::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => $operands),
                                   2 => array('filterOut2' => $next_operator),
        );
        
        $this->actions = array('transform'    => array( 1 => 'ELEMENT',
                                                       -1 => 'ELEMENT'
                                                      ),
                               'order'    => array( 1 => 2,
                                                   -1 => 1 ),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               'cleanIndex' => true
                               );
        $this->checkAuto();
        
        // @note ; next to another instruction
        $this->conditions = array( 0 => array('atom'  => 'Sequence' ),
                                   1 => array('token' => Sequence::$operators,
                                              'atom'  => 'none'),
        );
        
        $this->actions = array( 'transform'   => array(1 => 'DROP'));
        $this->checkAuto();

        // @note End of PHP script
        $this->conditions = array(-2 => array('filterOut2' => array_merge($yield_operator, array('T_OPEN_PARENTHESIS', 'T_BREAK', 'T_USE', 'T_AS'))), 
                                  -1 => array('atom'       => $operands,
                                              'notToken'   => 'T_ELSEIF', ),
                                   0 => array('token'      => Sequence::$operators,
                                              'atom'       => 'none'),
                                   1 => array('token'      => array('T_CLOSE_TAG', 'T_CLOSE_CURLY', 'T_END', 'T_CASE', 'T_DEFAULT', 'T_ENDIF', 'T_ELSEIF', 'T_ELSE', 'T_ENDWHILE', 'T_ENDFOR', 'T_CLOSE_CURLY'),
//                                              'atom'       => 'none'
                                              ),
        );
        
        $this->actions = array('transform'    => array(-1 => 'ELEMENT'),
                               //'order'       => array(-1 => 1),
                               'atom'        => 'Sequence',
                               'mergePrev2'   => array('Sequence' => 'ELEMENT'),
                               'cleanIndex'  => true);
        $this->checkAuto();

        // @note End of PHP script, alternative syntax
        $this->conditions = array(-3 => array('token'    => array('T_ELSE','T_OPEN_PARENTHESIS', 'T_USE', 'T_AS'), 
                                              'atom'     => 'none'), //'T_CLOSE_PARENTHESIS', 
                                  -2 => array('token'    => 'T_COLON',), 
                                  -1 => array('atom'     => $operands,
                                              'notToken' => 'T_ELSEIF', ),
                                   0 => array('token'    => Sequence::$operators,
                                              'atom'     => 'none'),
                                   1 => array('token'    => array('T_CLOSE_TAG', 'T_CLOSE_CURLY', 'T_END', 'T_CASE', 'T_DEFAULT', 'T_ENDIF', 'T_ELSEIF', 'T_ELSE', 'T_ENDWHILE', 'T_ENDFOR', 'T_ENDDECLARE', 'T_ENDFOREACH', ),
                                              'atom'     => 'none'),
        );
        
        $this->actions = array('makeEdge'    => array(-1 => 'ELEMENT'),
                               'order'       => array(-1 => 1),
                               'atom'        => 'Sequence',
                               'cleanIndex'  => true
                               );
        $this->checkAuto(); 

        // Sequence followed by ; followed by elseif atom.
        $this->conditions = array(  -1 => array('atom'  => 'Sequence'),
                                     0 => array('token' => Sequence::$operators),
                                     1 => array('token' => 'T_ENDFOREACH')
        );
        
        $this->actions = array('transform'   => array(0 => 'DROP'),
                               'keepIndexed' => true);
        $this->checkAuto(); 
       
        return $this->checkRemaining();
    }
}
?>