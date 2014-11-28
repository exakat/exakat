<?php

namespace Analyzer\Security;

use Analyzer;

class DirectInjection extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Security\\SensitiveArgument');
    }
    
    public function analyze() {
        $vars = $this->loadIni('php_incoming.ini');
        $vars = $vars['incoming'];

        // $_GET/_POST ... directly as argument of PHP functions
        $this->atomIs("Variable")
             ->code($vars)
             ->analyzerIs('Analyzer\\Security\\SensitiveArgument')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

        // $_GET/_POST ['index']... directly as argument of PHP functions
        $this->atomIs("Variable")
             ->code($vars)
             ->analyzerIs('Analyzer\\Security\\SensitiveArgument')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();

        // $_GET/_POST ['index']['index2']... directly as argument of PHP functions
        $this->atomIs("Variable")
             ->code($vars)
             ->raw('in("VARIABLE").loop(1){true}{ it.object.atom == "Array"}')
             ->analyzerIs('Analyzer\\Security\\SensitiveArgument')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS');
        $this->prepareQuery();
        
        // $_GET/_POST array... inside a string is useless
        // "$_GET/_POST ['index']"... inside a string or a concatenation
        $this->atomIs("Variable")
             ->code($vars)
             ->raw('in("VARIABLE").loop(1){true}{ it.object.atom == "Array"}')
             ->inIs('CONCAT');
        $this->prepareQuery();

        // "$_GET/_POST ['index']"... inside an operation is probably OK if not concatenation! 

        // foreach (looping on incoming variables)
        $this->atomIs("Variable")
             ->code($vars)
             ->raw('in("VARIABLE").loop(1){true}{ it.object.atom == "Array"}')
             ->inIs('SOURCE');
        $this->prepareQuery();
    }
}

?>