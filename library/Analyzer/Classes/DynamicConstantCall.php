<?php

namespace Analyzer\Classes;

use Analyzer;

class DynamicConstantCall extends Analyzer\Analyzer {
    public function analyze() {
        //constant("ThingIDs::$thing");
        // probably too weak. Needs to be completed with a check on variables built before
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\constant')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('String')
             ->regex('code', '::')
             ->back('first');
        $this->prepareQuery();

        //$r = new ReflectionClass('ThingIDs');
        //$id = $r->getConstant($thing);
        // probably too weak. Needs to be completed with a check on ReflectionClass
        $this->atomIs("Methodcall")
             ->outIs('METHOD')
             ->code('getConstant')
             ->back('first');
        $this->prepareQuery();



    }
}

?>
