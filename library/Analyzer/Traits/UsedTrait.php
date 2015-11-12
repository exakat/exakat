<?php

namespace Analyzer\Traits;

use Analyzer;

class UsedTrait extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $uses = $this->query('g.idx("atoms")[["atom":"Class"]].out("BLOCK").out("ELEMENT").has("atom", "Use").out("USE").fullnspath');

        $this->atomIs('Trait')
             ->fullnspath($uses)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
