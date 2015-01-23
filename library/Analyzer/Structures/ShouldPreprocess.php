<?php

namespace Analyzer\Structures;

use Analyzer;

class ShouldPreprocess extends Analyzer\Analyzer {
    public function analyze() {
        $dynamicAtoms = array('Variable', 'Property', 'Identifier', 'Functioncall', 'Magicconstant');
        
        $this->atomIs('Addition')
             ->noAtomInside($dynamicAtoms);
//        $this->printQuery();
        $this->prepareQuery();

        $this->atomIs('Multiplication')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Concatenation')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Bitshift')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Logical')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Not')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();
    }
}

?>
