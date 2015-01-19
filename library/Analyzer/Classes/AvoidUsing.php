<?php

namespace Analyzer\Classes;

use Analyzer;

class AvoidUsing extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array("MethodDefinition");
    }
    */
    
    public function analyze() {
        $classes = $this->config;
        
        if (empty($classes)) { 
            return null;
        }
        $classes = $this->makeFullNsPath($classes);
        
        // class may be used in a new
        $this->atomIs('New')
             ->outIs('NEW')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Staticmethodcall
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Staticproperty
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Staticconstant
        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Instanceof
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in a Typehint
        $this->atomIs("Typehint")
             ->outIs('CLASS')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in an extension
        $this->atomIs("Class")
             ->outIs(array('EXTENDS', 'IMPLEMENTS'))
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        // class may be used in an use
        $this->atomIs("Use")
             ->outIs('USE')
             ->fullnspath($classes)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\class_alias')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0);
        $this->prepareQuery();
    }
}

?>
