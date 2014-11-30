<?php

namespace Analyzer\Constants;

use Analyzer;

class ConstantUsage extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Extensions\\Extstandard');
    }
    
    public function analyze() {
        // Nsname that is not used somewhere else
        $this->atomIs("Nsname")
             ->hasNoIn(array('NEW', 'SUBNAME', 'USE', 'NAME', 'NAMESPACE', 'EXTENDS', 'IMPLEMENTS', 'CLASS'));
        $this->prepareQuery();

        // Identifier that is not used somewhere else
        $this->atomIs("Identifier")
             ->codeIsNot(array('true', 'false', 'null'))
             ->hasNoIn(array('NEW', 'SUBNAME', 'USE', 'NAME', 'NAMESPACE', 'CONSTANT', 'PROPERTY', 'CLASS', 'EXTENDS', 'IMPLEMENTS', 'CLASS', 'AS'));
        $this->prepareQuery();

        // special case for Boolean and Null
        $this->atomIs(array("Boolean", 'Null'));
        $this->prepareQuery();
        
        // defined('constant') : then the string is a constant
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\defined', '\\constant'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String');
        $this->prepareQuery();
    }
}

?>