<?php

namespace Analyzer\Security;

use Analyzer;

class DirectInjection extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Functions\\IsExtFunction');
    }
    
    public function analyze() {
        // $_GET/_POST ... directly as argument of PHP functions
        $this->atomIs("Variable")
             ->code(array('$_POST', '$_GET', '$_ENV', '$_REQUEST', '$_FILES', '$_COOKIE', '$PHP_SELF'))
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->analyzerIs('Analyzer\\Functions\\IsExtFunction');
        $this->prepareQuery();

        // $_GET/_POST ['index']... directly as argument of PHP functions
        $this->atomIs("Variable")
             ->code(array('$_POST', '$_GET', '$_ENV', '$_SERVER', '$_REQUEST', '$_FILES', '$_COOKIE', '$PHP_SELF'))
             ->inIs('VARIABLE')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->analyzerIs('Analyzer\\Functions\\IsExtFunction');
        $this->prepareQuery();

        // $_GET/_POST ['index']['index2']... directly as argument of PHP functions
        $this->atomIs("Variable")
             ->code(array('$_POST', '$_GET', '$_ENV', '$_SERVER', '$_REQUEST', '$_FILES', '$_COOKIE', '$PHP_SELF'))
             ->raw('in("VARIABLE").loop(1){true}{ it.object.atom == "Array"}')
             ->inIs('ARGUMENT')
             ->inIs('ARGUMENTS')
             ->analyzerIs('Analyzer\\Functions\\IsExtFunction');
        $this->prepareQuery();

        // $_GET/_POST... inside a string is useless
        // "$_GET/_POST ['index']"... inside a string or a concatenation
        $this->atomIs("Variable")
             ->code(array('$_POST', '$_GET', '$_ENV', '$_SERVER', '$_REQUEST', '$_FILES', '$_COOKIE', '$PHP_SELF'))
             ->raw('in("VARIABLE").loop(1){true}{ it.object.atom == "Array"}')
             ->inIs('CONCAT');
        $this->prepareQuery();

        // "$_GET/_POST ['index']"... inside an operation is probably OK

        // foreach 
        $this->atomIs("Variable")
             ->code(array('$_POST', '$_GET', '$_ENV', '$_SERVER', '$_REQUEST', '$_FILES', '$_COOKIE', '$PHP_SELF'))
             ->raw('in("VARIABLE").loop(1){true}{ it.object.atom == "Array"}')
             ->inIs('SOURCE');
        $this->prepareQuery();
    }
}

?>