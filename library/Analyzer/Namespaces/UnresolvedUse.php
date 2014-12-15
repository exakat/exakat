<?php

namespace Analyzer\Namespaces;

use Analyzer;

class UnresolvedUse extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\IsExtClass',
                     'Analyzer\\Interfaces\\IsExtInterface',
                     'Analyzer\\Traits\\IsExtTrait',
                     'Analyzer\\Namespaces\\KnownVendor'
                     );
    }

    public function analyze() {
        $this->atomIs("Use")
             ->outIs('USE')
             ->noClassDefinition()
             ->noNamespaceDefinition()
             ->noInterfaceDefinition()
             ->noTraitDefinition()
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->analyzerIsNot('Analyzer\\Interfaces\\IsExtInterface')
             ->analyzerIsNot('Analyzer\\Traits\\IsExtTrait')
             ->analyzerIsNot('Analyzer\\Namespaces\\KnownVendor')
             ;
        $this->prepareQuery();
    }
}

?>
