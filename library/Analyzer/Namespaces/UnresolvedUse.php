<?php

namespace Analyzer\Namespaces;

use Analyzer;

class UnresolvedUse extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\IsExtClass');
    }

    public function analyze() {
        $this->atomIs("Use")
             ->outIs('USE')
             ->noClassDefinition()
             ->noNamespaceDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass');
        $this->prepareQuery();
    }
}

?>