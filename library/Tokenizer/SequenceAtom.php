<?php

namespace Tokenizer;

class SequenceAtom extends TokenAuto {
    function _check() {
        $yield_operators =  array_merge(array('T_PROTECTED', 'T_PRIVATE', 'T_PUBLIC', 'T_STATIC', 'T_ABSTRACT', 'T_FINAL', 
                                              'T_CLOSE_PARENTHESIS', 'T_OPEN_PARENTHESIS', 'T_CLASS', 'T_EXTENDS', 'T_IMPLEMENTS', 
                                              'T_INTERFACE', 'T_ELSE', 'T_OBJECT_OPERATOR', 'T_QUOTE', 'T_COMMA', 'T_NAMESPACE', ),
                                        Assignation::$operators, Functioncall::$operators, _Dowhile::$operators);
        
        $next_operator = array_merge(array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 'T_COMMA', 
                                           'T_CLOSE_PARENTHESIS', 'T_CATCH', 
                                           'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_QUOTE_CLOSE', ), 
                                     Assignation::$operators, Logical::$operators);

        // @note instructions not separated by ; 
        $operands2 = array('Function', 'Ifthen', 'While', 'Class', 'Var', 'Global', 'Static', 'Logical', 
                           'Const', 'Ppp', 'Foreach', 'For', 'Assignation', 'Functioncall', 'Methodcall', 'Staticmethodcall',
                           'Abstract', 'Final', 'Switch', 'Include', 'Return', 'Ternary', 'String', 'Void', 'Dowhile', 'Comparison',
                           'Noscream', 'Property', 'Staticproperty', 'Label', 'Goto', 'Halt', 'Interface', 'Block', 'Break', 'Try', 
                           'Throw', 'Sequence','RawString', 'Phpcode', 'Use', 'Preplusplus', 'Postplusplus', 'New', 'Declare',
                           'Namespace', 
                             );
        $this->conditions = array(-1 => array('filterOut2' => $yield_operators), 
                                   0 => array('atom' => $operands2, 
                                              'notToken' => 'T_ELSEIF',
                                              'in_quote' => 'none'),
                                   1 => array('atom' => $operands2, 
                                              'notToken' => 'T_ELSEIF',
                                              'in_quote' => 'none'),
                                   2 => array('filterOut2' => $next_operator),
        );
        $this->actions = array('insertSequence'  => 1);
        $this->checkAuto();
       
        return $this->checkRemaining();
    }
}
?>