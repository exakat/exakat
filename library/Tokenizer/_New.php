<?php

namespace Tokenizer;

class _New extends TokenAuto {
    static public $operators = array('T_NEW');
    static public $atom = 'New';

    public function _check() {
        $this->conditions = array(0 => array('token'      => _New::$operators,
                                             'atom'       => 'none'),
                                  1 => array('atom'       => array('Functioncall', 'Constant', 'Variable', 'Methodcall', 'String',
                                                                   'Array', 'Property', 'Staticproperty', 'Staticmethodcall',
                                                                   'Nsname', 'Identifier',)),
                                  2 => array('filterOut2' => array('T_OPEN_PARENTHESIS', 'T_OBJECT_OPERATOR', 'T_NS_SEPARATOR',
                                                                   'T_OPEN_BRACKET', 'T_OPEN_CURLY', 'T_DOUBLE_COLON' )),
        );
        
        $this->actions = array('transform'    => array( 1 => 'NEW'),
                               'atom'         => 'New',
                               'cleanIndex'   => true,
                               'makeSequence' => 'it');
        $this->checkAuto();

        return false;
    }

    public function fullcode() {
        return <<<GREMLIN

it.setProperty('fullcode', "new " + it.out("NEW").next().getProperty('fullcode'));

GREMLIN;
    }
}

?>
