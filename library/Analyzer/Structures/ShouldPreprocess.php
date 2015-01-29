<?php

namespace Analyzer\Structures;

use Analyzer;

class ShouldPreprocess extends Analyzer\Analyzer {
    public function analyze() {
        $dynamicAtoms = array('Variable', 'Property', 'Identifier', 'Magicconstant');
        //'Functioncall', 
        
        $functionList = '"\\\\array", "\\\\strtolower", "\\\\strtoupper"';
        
        $this->atomIs('Addition')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Multiplication')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Concatenation')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Bitshift')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Logical')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Not')
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\join', '\\explode', '\\implode', '\\split'))
             ->raw('filter{ it.out().loop(1){true}{it.object.atom == "Functioncall"}.filter{!(it.fullnspath in ['.$functionList.'])}.any() == false}')
             ->noAtomInside($dynamicAtoms);
        $this->prepareQuery();
    }
}

?>
