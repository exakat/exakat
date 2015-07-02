<?php

namespace Analyzer\Structures;

use Analyzer;

class StaticLoop extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        // foreach with only one value
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('fullcode', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->filter(' it.out().loop(1){true}{it.object.atom == "Variable" && it.object.fullcode == blind}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // foreach with key value
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Keyvalue')

             ->outIs('KEY')
             ->savePropertyAs('fullcode', 'key')
             ->inIs('KEY')

             ->outIs('VALUE')
             ->savePropertyAs('fullcode', 'value')
             ->inIs('VALUE')

             ->back('first')
             ->outIs('BLOCK')
             ->filter(' it.out().loop(1){true}{it.object.atom == "Variable" && (it.object.fullcode == key || it.object.fullcode == value)}.any() == false')
             ->back('first');
        $this->prepareQuery();
        
        // foreach with complex structures (property, static property, arrays, references... ?)
        
        // for 
        $this->atomIs('For')
             ->outIs('INCREMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('For')
             ->outIs('INCREMENT')
             // collect all variables
             ->raw('sideEffect{ blind = []; it.out().loop(1){true}{it.object.atom == "Variable"}.aggregate(blind){it.fullcode}.iterate(); }')
             ->inIs('INCREMENT')
             ->outIs('BLOCK')
             // check if the variables are used here
             ->filter(' it.out().loop(1){true}{it.object.atom == "Variable" && it.object.fullcode in blind}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // for with complex structures (property, static property, arrays, references... ?)

        // do...while 
        $this->atomIs('Dowhile')
             ->outIs('CONDITION')
             // collect all variables
             ->raw('sideEffect{ blind = []; it.out().loop(1){true}{it.object.atom == "Variable"}.aggregate(blind){it.fullcode}.iterate(); }')
             ->inIs('CONDITION')
             ->outIs('BLOCK')
             // check if the variables are used here
             ->filter(' it.out().loop(1){true}{it.object.atom == "Variable" && it.object.fullcode in blind}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // do while with complex structures (property, static property, arrays, references... ?)

        // while 
        $this->atomIs('While')
             ->outIs('CONDITION')
             // collect all variables
             ->raw('sideEffect{ blind = []; it.out().loop(1){true}{it.object.atom == "Variable"}.aggregate(blind){it.fullcode}.iterate(); }')
             ->inIs('CONDITION')
             ->outIs('BLOCK')
             // check if the variables are used here
             ->filter(' it.out().loop(1){true}{it.object.atom == "Variable" && it.object.fullcode in blind}.any() == false')
             ->back('first');
        $this->prepareQuery();

        // while with complex structures (property, static property, arrays, references... ?)
    }
}

?>
