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
                          'Bitshift', 'Void', 'Dowhile', 'Halt', 'Interface', 'Block', 'RawString', 
                          'Phpcode', 'Namespace', 
                           );
        
        $next_operator = array_merge(array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_COMMA', 'T_CLOSE_PARENTHESIS', 'T_CATCH',
                                           'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_ELSEIF' ), 
                                     Assignation::$operators, Logical::$operators);

        // @note instructions not separated by ; 
        $operands2 = array('Function', 'Ifthen', 'While', 'Class', 'Var', 'Global', 'Static', 'Logical', 
                           'Const', 'Ppp', 'Foreach', 'For', 'Assignation', 'Functioncall', 'Methodcall', 'Staticmethodcall',
                           'Abstract', 'Final', 'Switch', 'Include', 'Return', 'Ternary', 'String', 'Void', 'Dowhile', 'Comparison',
                           'Noscream', 'Property', 'Staticproperty', 'Label', 'Goto', 'Halt', 'Interface', 'Block', 'Break', 'Try', 
                           'Throw', 'Sequence','RawString', 'Phpcode', 
                             );
        $yield_operators =  array_merge(array('T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC', 'T_STATIC', 'T_ABSTRACT', 'T_FINAL', 
                                              'T_CLOSE_PARENTHESIS', 'T_OPEN_PARENTHESIS', 'T_CLASS', 'T_EXTENDS', 'T_IMPLEMENTS', 
                                              'T_INTERFACE', 'T_ELSE', 'T_OBJECT_OPERATOR'),
                                        Assignation::$operators, Functioncall::$operators);
        
        $this->conditions = array(-1 => array('filterOut2' => $yield_operators), 
                                   0 => array('atom' => $operands2, 'notToken' => 'T_ELSEIF'),
                                   1 => array('atom' => $operands2 ),
                                   2 => array('filterOut2' => $next_operator),
        );
        $this->actions = array('insertSequence'  => true);
        $this->checkAuto();
       
        return $this->checkRemaining();
    }
}
?>