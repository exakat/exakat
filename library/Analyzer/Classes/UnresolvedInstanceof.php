<?php

namespace Analyzer\Classes;

use Analyzer;

class UnresolvedInstanceof extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\IsExtClass',
                     'Analyzer\\Classes\\IsVendor',);
    }

    public function analyze() {
        $classes = $this->loadIni('php_classes.ini');
        $classes = $classes['classes'];
        $classes = $this->makeFullNsPath($classes);
        
        $this->atomIs("Instanceof")
             ->outIs('CLASS')
             ->noClassDefinition()
             ->noInterfaceDefinition()
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->analyzerIsNot('Analyzer\\Classes\\IsVendor')
             ->fullnspathIsNot($classes)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
