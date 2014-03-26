<?php

namespace Tokenizer;

class _Function extends TokenAuto {
    static public $operators = array('T_FUNCTION');
    
    public function _check() {
        // function x(args) {}
        $this->conditions = array(0 => array('token' => _Function::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Identifier', 'Boolean')),
                                  2 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  3 => array('atom' => 'Arguments'),
                                  4 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  5 => array('atom' => 'Sequence'),
        );
        
        $this->actions = array('transform'     => array( 1 => 'NAME',
                                                         2 => 'DROP',
                                                         3 => 'ARGUMENTS',
                                                         4 => 'DROP', 
                                                         5 => 'BLOCK'),
                               'makeSequence'  => 'it',
                               'atom'          => 'Function',
                               'checkTypehint' => 'Function',
                               'cleanIndex'    => true);
        $this->checkAuto();

        // function x(args); for interfaces
        $this->conditions = array(0 => array('token' =>  _Function::$operators,
                                             'atom' => 'none'),
                                  1 => array('atom' => array('Identifier', 'Boolean')),
                                  2 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  3 => array('atom' => 'Arguments'),
                                  4 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  5 => array('token' => 'T_SEMICOLON'),
        );
        
        $this->actions = array('transform'     => array( 1 => 'NAME',
                                                         2 => 'DROP',
                                                         3 => 'ARGUMENTS',
                                                         4 => 'DROP', 
                                                         5 => 'DROP'),
                               'atom'          => 'Function',
                               'checkTypehint' => 'Function',
                               'makeSequence'  => 'it',
                               'cleanIndex'    => true);
        $this->checkAuto();

        // lambda function (no name)
        $this->conditions = array(0 => array('token' =>  _Function::$operators,
                                             'atom' => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom' => 'Arguments'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('atom' => 'Sequence')
        );
        
        $this->actions = array('to_lambda'  => true,
                               'atom'       => 'Function',
                               'checkTypehint' => 'Function',
                               'cleanIndex' => true);
        $this->checkAuto();

        // lambda function ($x) use ($y)
        $this->conditions = array(0 => array('token' =>  _Function::$operators,
                                             'atom'  => 'none'),
                                  1 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  2 => array('atom'  => 'Arguments'),
                                  3 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  4 => array('token' => 'T_USE'),
                                  5 => array('token' => 'T_OPEN_PARENTHESIS'),
                                  6 => array('atom'  => 'Arguments'),
                                  7 => array('token' => 'T_CLOSE_PARENTHESIS'),
                                  8 => array('atom'  => 'Sequence')
        );
        
        $this->actions = array('to_lambda_use'  => true,
                               'atom'           => 'Function',
                               'checkTypehint'  => 'Function',
                               'cleanIndex'     => true);
        $this->checkAuto();

        return $this->checkRemaining();
    }

    public function fullcode() {
        return <<<GREMLIN

fullcode.fullcode = '';

// for function
fullcode.filter{it.out("USE").count() == 0 && it.out("NAME").count() == 1 && it.out("BLOCK").count() == 0}.each{ fullcode.fullcode = "function " + fullcode.out("NAME").next().fullcode + " " + fullcode.out("ARGUMENTS").next().fullcode + " ;";}

fullcode.filter{it.out("USE").count() == 0 && it.out("NAME").count() == 1 && it.out("BLOCK").count() == 1}.each{ fullcode.fullcode = "function " + fullcode.out("NAME").next().fullcode + " " + fullcode.out("ARGUMENTS").next().fullcode + " " + fullcode.out("BLOCK").next().fullcode;}

fullcode.filter{it.out("USE").count() == 0 && it.out("NAME").count() == 0 && it.out("BLOCK").count() == 1}.each{ fullcode.fullcode = "function " + fullcode.out("ARGUMENTS").next().fullcode + " " + fullcode.out("BLOCK").next().fullcode;}

fullcode.filter{it.out("USE").count() == 1}.each{ fullcode.fullcode = "function " + fullcode.out("ARGUMENTS").next().fullcode + " use " + fullcode.out("USE").next().fullcode + " " + fullcode.out("BLOCK").next().fullcode;}

// for PPP
if (fullcode.out('DEFINE').count() == 1) { fullcode.fullcode = fullcode.fullcode + fullcode.out('DEFINE').next().fullcode; }
if (fullcode.out('VALUE').hasNot('atom', 'Void').count() == 1) { fullcode.fullcode = fullcode.fullcode + ' = ' + fullcode.out('VALUE').next().fullcode; }

// optional attributesæ
if (fullcode.out('ABSTRACT').count() == 1) { fullcode.fullcode = 'abstract ' + fullcode.fullcode; }
if (fullcode.out('FINAL').count() == 1) { fullcode.fullcode = 'final ' + fullcode.fullcode; }
if (fullcode.out('STATIC').count() == 1) { fullcode.fullcode = 'static ' + fullcode.fullcode; }

if (fullcode.out('PUBLIC').count() == 1) { fullcode.fullcode = 'public ' + fullcode.fullcode; }
if (fullcode.out('PROTECTED').count() == 1) { fullcode.fullcode = 'protected ' + fullcode.fullcode; }
if (fullcode.out('PRIVATE').count() == 1) { fullcode.fullcode = 'private ' + fullcode.fullcode; }

GREMLIN;
    }
}

?>