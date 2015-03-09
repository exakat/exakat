<?php

namespace Analyzer\Interfaces;

use Analyzer;

class UselessInterfaces extends Analyzer\Analyzer {

    public function analyze() {
        // interface not used in a instanceof nor a Typehint
        $this->atomIs('Interface')
             ->savePropertyAs('fullnspath', 'interfacedns')
             ->raw('filter{ g.idx("atoms")[["atom":"Class"]].out("IMPLEMENTS").has("fullnspath", interfacedns).any() || 
                            g.idx("atoms")[["atom":"Interface"]].out("EXTENDS").has("fullnspath", interfacedns).any()}')
             ->raw('filter{ g.idx("atoms")[["atom":"Instanceof"]].out("CLASS").has("fullnspath", interfacedns).any() == false}')
             ->raw('filter{ g.idx("atoms")[["atom":"Typehint"]].out("CLASS").has("fullnspath", interfacedns).any() == false}');
        $this->prepareQuery();
    }
}

?>
