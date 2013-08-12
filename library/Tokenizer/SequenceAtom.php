<?php

namespace Tokenizer;

class SequenceAtom extends TokenAuto {
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
                          'Bitshift', 'Void', 'Dowhile', 
                           );
        

        $yield_operator = array('T_ECHO', 'T_PRINT', 'T_DOT', 'T_AT', 'T_OBJECT_OPERATOR', 'T_BANG',
                                'T_DOUBLE_COLON',  'T_NEW', 'T_INSTANCEOF', 
                                'T_AND', 'T_QUOTE', 'T_DOLLAR', 'T_VAR', 'T_CONST', 'T_COMMA',
                                'T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC', 'T_INC', 'T_DEC', 'T_GLOBAL', 'T_NS_SEPARATOR',
                                'T_GOTO', 'T_STATIC', 'T_OPEN_PARENTHESIS',  'T_ELSE', 'T_ELSEIF', 'T_CLOSE_PARENTHESIS',
                                'T_THROW', 'T_CATCH', 'T_ABSTRACT', 'T_CASE', 'T_DEFAULT', 
                                 );
                                 //'T_IF', 'T_COLON',
        $yield_operator = array_merge($yield_operator, Assignation::$operators, Addition::$operators, Multiplication::$operators, Comparison::$operators, Cast::$operators, Logical::$operators, Bitshift::$operators, 
                                        _Include::$operators );
        $next_operator = array_merge(array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_COMMA', 'T_CLOSE_PARENTHESIS', 'T_CATCH',
                                           'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_ELSEIF' ), 
                                     Assignation::$operators, Logical::$operators);

        // @note instructions not separated by ; 
        $operands2 = array('Function', 'Ifthen', 'While', 'Class', 'Var', 'Global', 'Static', 'Logical', 
                           'Const', 'Ppp', 'Foreach', 'For', 'Assignation', 'Functioncall', 'Methodcall', 'Staticmethodcall',
                           'Abstract', 'Final', 'Switch', 'Include', 'Return', 'Ternary', 'String', 'Void', 'Dowhile', 'Comparison',
                           'Noscream', 'Property', 'Staticproperty', 'Label', 'Goto', 'Halt', 'Interface', 'Block',  );
        $this->conditions = array(-1 => array('filterOut2' => array_merge(array('T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC', 'T_STATIC', 'T_ABSTRACT', 'T_FINAL', 'T_CLOSE_PARENTHESIS', 'T_OPEN_PARENTHESIS', 'T_CLASS', 'T_EXTENDS', 'T_IMPLEMENTS', 'T_INTERFACE', ),
                                                             Assignation::$operators)), 
                                   0 => array('atom' => $operands2, 'notToken' => 'T_ELSEIF'),
                                   1 => array('atom' => $operands2, ),
                                   2 => array('filterOut2' => $next_operator),
        );
        $this->actions = array('insertSequence'  => true);
        $this->checkAuto();

        // @note sequence followed by another instruction
        $this->conditions = array(-1 => array('filterOut' => $yield_operator), 
                                   0 => array('atom' => 'Sequence'),
                                   1 => array('atom' => $operands, 'notToken' => 'T_ELSEIF'),
                                   2 => array('filterOut' => $next_operator),
        );
        
        $this->actions = array('transform'  => array(1 => 'ELEMENT'),
                               'order'      => array(1 =>  1),
                               'mergeNext'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               'cleanIndex' => true );
        $this->checkAuto();
        
        // @note sequence preceded by another instruction
        $this->conditions = array(-2 => array('filterOut2' => array_merge($yield_operator, 
                                                                          array('T_IF'))), 
                                  -1 => array('atom' => $operands, 'notToken' => 'T_ELSEIF' ),
                                   0 => array('atom' => 'Sequence')
        );
        
        $this->actions = array('order'      => array(-1 =>  1),
                               'mergePrev'  => array('Sequence' => 'ELEMENT'), 
                               'atom'       => 'Sequence',
                               );
        $this->checkAuto();

        // @note sequence next to another sequence
        $this->conditions = array(//-1 => array('filterOut' => $yield_operator), 
                                   0 => array('atom' => 'Sequence' ),
                                   1 => array('atom' => 'Sequence')
        );
        
        $this->actions = array( 'transform'  => array(1 => 'ELEMENT'),
                                'mergeNext'  => array('Sequence' => 'ELEMENT'),
                                'cleanIndex' => true);
        $this->checkAuto();
       
        return $this->checkRemaining();
    }
}
?>