<?php

namespace Analyzer\Classes;

use Analyzer;

class CitSameName extends Analyzer\Analyzer {
    public function analyze() {
        // Classes - Interfaces
        $this->atomIs("Class")
             ->outIs('NAME')
             ->raw("filter{ g.idx('atoms')[['atom':'Interface']].out('NAME').next().code == it.code}")
             ->back('first');
        $this->prepareQuery();

        // Classes - Traits
        $this->atomIs("Class")
             ->analyzerIsNot('Analyzer\\Classes\\CitSameName')
             ->outIs('NAME')
             ->raw("filter{ g.idx('atoms')[['atom':'Trait']].out('NAME').next().code == it.code}")
             ->back('first');
        $this->prepareQuery();

        // Interfaces - Traits
        $this->atomIs("Interface")
             ->outIs('NAME')
             ->raw("filter{ g.idx('atoms')[['atom':'Trait']].out('NAME').next().code == it.code}")
             ->back('first');
        $this->prepareQuery();
    }
}

?>
