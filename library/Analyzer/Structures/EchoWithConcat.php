<?php

namespace Analyzer\Structures;

use Analyzer;

class EchoWithConcat extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array("MethodDefinition");
    }
    */
    
    public function analyze() {
        //echo 'should'.'also'.$be.' with comma';
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_ECHO', 'T_PRINT'))
             ->fullnspath(array('\\echo', '\\print'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Concatenation')
             ->back('first');
        $this->prepareQuery();

        //echo 'should'.'also'.$be.' with comma';
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_ECHO', 'T_PRINT'))
             ->fullnspath(array('\\echo', '\\print'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('String')
             ->outIs('CONTAIN')
             ->atomIs('Concatenation')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
