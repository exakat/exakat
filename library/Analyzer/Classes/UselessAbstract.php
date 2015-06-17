<?php

namespace Analyzer\Classes;

use Analyzer;

class UselessAbstract extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('OnlyStaticMethods');
    }
    
    public function analyze() {
        $this->atomIs('Class')
             ->analyzerIsNot('OnlyStaticMethods')
             ->filter('it.out("BLOCK").out("ELEMENT").any()')
             ->hasOut('ABSTRACT')
             ->savePropertyAs('fullnspath', 'fnp')
             ->filter('g.idx("atoms")[["atom":"Class"]].out("EXTENDS").has("fullnspath", fnp).any() == false')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
