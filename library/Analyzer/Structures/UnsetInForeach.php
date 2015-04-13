<?php

namespace Analyzer\Structures;

use Analyzer;

class UnsetInForeach extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        // foreach($a as $v) { unset($v); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE(array('KEY', 'VALUE'))
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $k => $v) { unset($v); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $k => $v) { unset($k); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('KEY')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Variable')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();


        // foreach($a as $v) { unset($v[1]); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $k => $v) { unset($k[1]); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('KEY')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();

        // foreach($a as $k => $v) { unset($v[1]); }
        $this->atomIs('Foreach')
             ->outIs('VALUE')
             ->outIsIE('VALUE')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'blind')
             ->back('first')
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->tokenIs('T_UNSET')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Array')
             ->outIs('VARIABLE')
             ->samePropertyAs('code', 'blind')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
