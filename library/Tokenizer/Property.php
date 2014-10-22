<?php

namespace Tokenizer;

class Property extends TokenAuto {
    static public $operators = array('T_OBJECT_OPERATOR');
    static public $atom = 'Property';
    
    public function _check() {
        $operands = array('Variable', 'Property', 'Array', 'Staticmethodcall', 'Staticproperty', 'Methodcall', 'Functioncall');

        // $object->property{1}
        $this->conditions = array( -2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                   -1 => array('atom' => $operands), 
                                    0 => array('token' => Property::$operators),
                                    1 => array('atom' => array('String', 'Variable', 'Array', 'Identifier', 'Boolean')),
                                    2 => array('token' => array('T_OPEN_CURLY', 'T_OPEN_BRACKET')), 
                                    );
        
        $this->actions = array('makeEdge'     => array( -1 => 'OBJECT',
                                                         1 => 'PROPERTY'),
                               'atom'         => 'Property',
                               'cleanIndex'   => true);
        $this->checkAuto(); 
        
        // $object->property
        $this->conditions = array( -2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                   -1 => array('atom' => $operands), 
                                    0 => array('token' => Property::$operators),
                                    1 => array('atom' => array('String', 'Variable', 'Array', 'Identifier', 'Boolean')),
                                    2 => array('filterOut' => array('T_OPEN_PARENTHESIS', 'T_OPEN_CURLY', 'T_OPEN_BRACKET')), 
                                    );
        
        $this->actions = array('makeEdge'     => array( -1 => 'OBJECT',
                                                         1 => 'PROPERTY'),
                               'atom'         => 'Property',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto(); 

        // $object->{property}
        $this->conditions = array( -2 => array('filterOut' => array('T_OBJECT_OPERATOR', 'T_DOUBLE_COLON')),
                                   -1 => array('atom'  => $operands), 
                                    0 => array('token' => Property::$operators),
                                    1 => array('token' => 'T_OPEN_CURLY'),
                                    2 => array('atom'  => 'yes'),
                                    3 => array('token' => 'T_CLOSE_CURLY'),
                                    4 => array('filterOut' => array('T_OPEN_PARENTHESIS')),
                                    );
        
        $this->actions = array('transform'    => array( -1 => 'OBJECT',
                                                         1 => 'DROP',
                                                         2 => 'PROPERTY',
                                                         3 => 'DROP'),
                               'atom'         => 'Property',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto(); 

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN
// case for \$v() 
fullcode.out("NAME").each{ fullcode.fullcode = it.fullcode }

if (fullcode.out('PROPERTY').next().atom == 'Identifier') { 
    fullcode.setProperty('fullcode', fullcode.out("OBJECT").next().getProperty('fullcode') + "->" + fullcode.out("PROPERTY").next().getProperty('fullcode'));
} else {
    fullcode.setProperty('fullcode', fullcode.out("OBJECT").next().getProperty('fullcode') + "->{" + fullcode.out("PROPERTY").next().getProperty('fullcode') + "}");
}

GREMLIN;
    }
}

?>