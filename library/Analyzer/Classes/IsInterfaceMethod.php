<?php

namespace Analyzer\Classes;

use Analyzer;

class IsInterfaceMethod extends Analyzer\Analyzer {

    public function dependsOn() {
        return array('MethodDefinition');
    }
    
    public function analyze() {
        // locally defined interface
        $this->atomIs('Function')
             ->outIs('NAME')
             ->analyzerIs('MethodDefinition')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->outIs('IMPLEMENTS')
             ->interfaceDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Function')
             ->outIs('NAME')
             ->analyzerIs('MethodDefinition')
             ->savePropertyAs('code', 'name')
             ->goToClass()
             ->goToAllParents()
             ->outIs('IMPLEMENTS')
             ->interfaceDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->samePropertyAs('code', 'name')
             ->back('first');
        $this->prepareQuery();

        // PHP or extension defined interface
        $interfaces = $this->loadIni('php_interfaces_methods.ini', 'interface');
        
        foreach($interfaces as $interface => $methods) {
            $methods = explode(',', $methods);

            $this->atomIs('Function')
                 ->outIs('NAME')
                 ->analyzerIs('MethodDefinition')
                 ->code($methods)
                 ->goToClass()
                 ->outIs('IMPLEMENTS')
                 ->fullnspath('\\'.$interface)
                 ->back('first');
            $this->prepareQuery();

            $this->atomIs('Function')
                 ->outIs('NAME')
                 ->analyzerIs('MethodDefinition')
                 ->code($methods)
                 ->goToClass()
                 ->goToAllParents()
                 ->outIs('IMPLEMENTS')
                 ->fullnspath('\\'.$interface)
                 ->back('first');
            $this->prepareQuery();
        }

    }
}

?>
