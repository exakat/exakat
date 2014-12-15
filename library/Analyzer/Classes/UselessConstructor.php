<?php

namespace Analyzer\Classes;

use Analyzer;

class UselessConstructor extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\Constructor');
    }

    public function analyze() {
        // class a (no extends, no implements)
        $this->atomIs("Class")
             ->hasNoOut('EXTENDS')
             ->hasNoOut('IMPLEMENTS')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIs('Analyzer\\Classes\\Constructor')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first');
        $this->prepareQuery();

        // class a with extends, one level
        $this->atomIs("Class")
             ->hasOut('EXTENDS')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIs('Analyzer\\Classes\\Constructor')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->hasNoOut('EXTENDS')
             ->hasNoOut('IMPLEMENTS')
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\Constructor").any()}.any() == false }')
             ->back('first');
        $this->prepareQuery();

        // class a with extends, two level
        $this->atomIs("Class")
             ->hasOut('EXTENDS')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->analyzerIs('Analyzer\\Classes\\Constructor')
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Void')
             ->back('first')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->hasOut('EXTENDS')
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\Constructor").any()}.any() == false }')
             ->outIs('EXTENDS')
             ->classDefinition()
             ->raw('filter{ it.out("BLOCK").out("ELEMENT").has("atom", "Function").filter{ it.in("ANALYZED").has("code", "Analyzer\\\\Classes\\\\Constructor").any()}.any() == false }')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
