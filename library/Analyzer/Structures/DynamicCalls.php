<?php

namespace Analyzer\Structures;

use Analyzer;

class DynamicCalls extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        // dynamic constants
        $this->atomFunctionIs('constant');
        $this->prepareQuery();

        // $$v variable variables
        $this->atomIs('Variable')
             ->outIs('NAME')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // dynamic functioncall
        $this->atomIs('Functioncall')
             ->outIs('NAME')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();

        // dynamic new
        $this->atomIs('New')
             ->outIs('NEW')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->back('first');
        $this->prepareQuery();

        // property
        // $$o->p
        $this->atomIs('Property')
             ->outIs('OBJECT')
             ->atomIsNot(array('Variable', 'Methodcall', 'Property', 'Staticproperty', 'Staticmethodcall', 'Array'))
             ->back('first');
        $this->prepareQuery();

        // $o->{$p}
        $this->atomIs('Property')
             ->outIs('PROPERTY')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // method
        // $$o->m()
        $this->atomIs('Methodcall')
             ->outIs('OBJECT')
             ->atomIsNot(array('Variable', 'Methodcall', 'Property', 'Staticproperty', 'Staticmethodcall', 'Array'))
             ->back('first');
        $this->prepareQuery();

        // $o->{$m}()
        $this->atomIs('Methodcall')
             ->outIs('PROPERTY')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

        // static constants
        // use constant() or reflexion
        
        
        // static property
        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR', 'T_OPEN_BRACKET'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->back('first');
        $this->prepareQuery();

        // $o->{$p}
        $this->atomIs('Staticproperty')
             ->outIs('PROPERTY')
             ->tokenIsNot(array('T_VARIABLE', 'T_OPEN_BRACKET'))
             ->back('first');
        $this->prepareQuery();

        // static methods
        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->tokenIsNot(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot(array('self', 'parent', 'static'))
             ->back('first');
        $this->prepareQuery();

        // $o::{$p}()
        $this->atomIs('Staticmethodcall')
             ->outIs('METHOD')
             ->tokenIsNot('T_STRING')
             ->back('first');
        $this->prepareQuery();

// class_alias
// call_user_func_array and co
// classes in names
// support reflection
    }
}

?>