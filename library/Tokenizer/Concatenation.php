<?php

namespace Tokenizer;

class Concatenation extends TokenAuto {
    public static $operators = array('T_DOT');
    static public $atom = 'Concatenation';
    
    public function _check() {
        $operands = array('String', 'Identifier', 'Integer', 'Float', 'Not', 'Variable','Array', 'Concatenation', 'Sign', 'Array',
                          'Functioncall', 'Noscream', 'Staticproperty', 'Staticmethodcall', 'Staticconstant', 'Staticclass',
                          'Methodcall', 'Parenthesis', 'Magicconstant', 'Property', 'Multiplication', 'Addition', 'Power',
                          'Preplusplus', 'Postplusplus', 'Cast', 'Assignation', 'Nsname' );
        
        $this->conditions = array(-2 => array('filterOut' => array_merge(Addition::$operators, Multiplication::$operators,
                                                                         Preplusplus::$operators, Power::$operators, 
                                                            array('T_AT', 'T_NOT', 'T_DOUBLE_COLON', 'T_OBJECT_OPERATOR', 'T_DOLLAR'))), 
                                  -1 => array('atom'  => $operands ),
                                   0 => array('token' => Concatenation::$operators,
                                              'atom'  => 'none'),
                                   1 => array('atom'  => $operands),
                                   2 => array('filterOut' => array_merge(array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_DOUBLE_COLON', 
                                                                               'T_OPEN_CURLY', 'T_OPEN_BRACKET'),
                                                                    Assignation::$operators, Preplusplus::$operators)),
        ); 
        
        $this->actions = array('makeEdge'   => array( 1 => 'CONCAT',
                                                     -1 => 'CONCAT'
                                                      ),
                               'rank'       => array( 1 => 1,
                                                     -1 => 0 ),
                               'mergeNext'  => array('Concatenation' => 'CONCAT'), 
                               'atom'       => 'Concatenation',
                               'cleanIndex' => true,
                               'makeSequence' => 'clean',
                               );
        
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
s = [];
fullcode.out("CONCAT").sort{it.rank}._().each{ s.add(it.fullcode); };
fullcode.setProperty('fullcode', "" + s.join(" . ") + "");
fullcode.setProperty('count', s.size());

GREMLIN;
    }

}
?>