<?php

namespace Analyzer\Namespaces;

use Analyzer;

class UnresolvedUse extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Use")
             ->outIs('USE')
             ->noClassDefinition()
             ->noNamespaceDefinition();
        $this->prepareQuery();
    }
}

?>