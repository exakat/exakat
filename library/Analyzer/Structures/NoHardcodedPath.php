<?php

namespace Analyzer\Structures;

use Analyzer;

class NoHardcodedPath extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $functions = array('glob', 'fopen', 'file', 'file_get_contents', 'file_put_contents', 'unlink',
                           'opendir', 'rmdir', 'mkdir');
        // string literal fopen('a', 'r');
        // may need some regex to exclude http...
        $this->atomFunctionIs($functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->back('first');
        $this->prepareQuery();

        // string literal fopen("a$b", 'r');
        // may need some regex to exclude http...
        $this->atomFunctionIs($functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->tokenIs('T_QUOTE')
             ->outIs('CONTAIN')
             ->outIs('CONCAT')
             ->is('rank', 0)
             ->tokenIs('T_ENCAPSED_AND_WHITESPACE')
             ->back('first');
        $this->prepareQuery();

        // string literal fopen('a.$b, 'r');
        // may need some regex to exclude http...
        $this->atomFunctionIs($functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('Concatenation')
             ->outIs('CONCAT')
             ->is('rank', 0)
             ->tokenIs('T_CONSTANT_ENCAPSED_STRING')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
