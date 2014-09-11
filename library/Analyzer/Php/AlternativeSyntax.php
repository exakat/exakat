<?php

namespace Analyzer\Php;

use Analyzer;

class AlternativeSyntax extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Ifthen")
             ->is('alternative', "true");
        $this->prepareQuery();
        
        $this->atomIs("Switch")
             ->is('alternative', "true");
        $this->prepareQuery();

        $this->atomIs("For")
             ->is('alternative', "true");
        $this->prepareQuery();

        $this->atomIs("Foreach")
             ->is('alternative', "true");
        $this->prepareQuery();

        $this->atomIs("While")
             ->is('alternative', "true");
        $this->prepareQuery();
    }
}

?>