<?php

namespace Analyzer\Structures;

use Analyzer;

class NoChangeIncomingVariables extends Analyzer\Analyzer {
    public function analyze() {
        $incomingVariables = array('$_GET','$_POST','$_REQUEST','$_FILES',
                                    '$_ENV', '$_SERVER',
                                    '$PHP_SELF','$HTTP_RAW_POST_DATA'); 
        //'$_COOKIE', '$_SESSION' : those are OK
        
        // full array unset($_GET);
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // array unset($_GET['level1']);
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // array unset($_GET['level1']['level2']);
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_UNSET')
             ->fullnspath('\\unset')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // Assignation full array $_COOKIE = 22;
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // assignation index $_FILES['level1']
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // assignation index $_FILES['level1'][]
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Arrayappend')
             ->outIs('VARIABLE')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();

        // assignation index $_FILES['level1']['level2']
        $this->atomIs('Assignation')
             ->outIs('LEFT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->code($incomingVariables)
             ->back('first');
        $this->prepareQuery();
    }
}

?>