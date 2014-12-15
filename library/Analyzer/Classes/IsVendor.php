<?php

namespace Analyzer\Classes;

use Analyzer;

class IsVendor extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Namespaces\\KnownVendor');
    }
    
    public function analyze() {
        // static constants
        // for aliases 
        $this->atomIs("Staticconstant")
             ->outIs('CLASS')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK', 'CODE')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('first');
        $this->prepareQuery();

        // for direct naming 

        // static methods
        // for aliases 
        $this->atomIs("Staticmethodcall")
             ->outIs('CLASS')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK', 'CODE')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('first');
        $this->prepareQuery();

        // for direct naming 

        // static properties
        // for aliases 
        $this->atomIs("Staticproperty")
             ->outIs('CLASS')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK', 'CODE')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('first');
        $this->prepareQuery();

        // for direct naming 

        // Instanceof
        // for aliases 
        $this->atomIs("Instanceof")
             ->outIs('CLASS')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK', 'CODE')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('first');
        $this->prepareQuery();

        // for direct naming 

        // New
        // for aliases with namespaces
        $this->atomIs("New")
             ->outIs('NEW')
             ->_as('classe')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('FILE')
             ->outIsIE('ELEMENT')
             ->outIs('CODE')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('classe');
        $this->prepareQuery();

        // for aliases without namespaces
        $this->atomIs("New")
             ->outIs('NEW')
             ->_as('classe')
             ->savePropertyAs('code', 'classe')
             ->goToNamespace()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Use')
             ->outIs('USE')
             ->samePropertyAs('alias', 'classe')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('classe');
        $this->prepareQuery();

        // for direct naming 
        $this->atomIs("New")
             ->outIs('NEW')
             ->_as('classe')
             ->tokenIs('T_NS_SEPARATOR')
             ->analyzerIs('Analyzer\\Namespaces\\KnownVendor')
             ->back('classe');
        $this->prepareQuery();

    }
}

?>
