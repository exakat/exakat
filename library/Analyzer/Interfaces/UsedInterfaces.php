<?php

namespace Analyzer\Interfaces;

use Analyzer;

class UsedInterfaces extends Analyzer\Analyzer {
    public function analyze() {
        // interface used in a class definition
        $this->atomIs('Interface')
             ->savePropertyAs('fullnspath', 'interfacedns')
             ->raw('filter{ g.idx("atoms")[["atom":"Class"]].out("IMPLEMENTS").has("fullnspath", interfacedns).any()}');
        $this->prepareQuery();

        // interface used in a class definition
        $this->atomIs('Interface')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'interfacedns')
             ->raw('filter{ g.idx("atoms")[["atom":"Interface"]].out("EXTENDS").has("fullnspath", interfacedns).any()}');
        $this->prepareQuery();

        // interface used in a instanceof
        $this->atomIs('Interface')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'interfacedns')
             ->raw('filter{ g.idx("atoms")[["atom":"Instanceof"]].out("CLASS").has("fullnspath", interfacedns).any()}');
        $this->prepareQuery();
        
        // interface used in a instanceof
        $this->atomIs('Interface')
             ->analyzerIsNot('self')
             ->savePropertyAs('fullnspath', 'interfacedns')
             ->raw('filter{ g.idx("atoms")[["atom":"Typehint"]].out("CLASS").has("fullnspath", interfacedns).any()}');
        $this->prepareQuery();
    }
}

?>
