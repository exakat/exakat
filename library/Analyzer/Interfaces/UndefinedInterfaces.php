<?php

namespace Analyzer\Interfaces;

use Analyzer;

class UndefinedInterfaces extends Analyzer\Analyzer {
    public function analyze() {
        // interface used in a instanceof nor a Typehint but not defined
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->noClassDefinition()
             ->noInterfaceDefinition();
        $this->prepareQuery();

        $this->atomIs('Typehint')
             ->outIs('CLASS')
             ->noClassDefinition()
             ->noInterfaceDefinition();
        $this->prepareQuery();
    }
}

?>
