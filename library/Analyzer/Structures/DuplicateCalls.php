<?php

namespace Analyzer\Structures;

use Analyzer;

class DuplicateCalls extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        // This is counting ALL occurences as itself. 
        $this->atomIs('Methodcall')
             ->hasNoIn('METHOD')
             ->fetchContext()
             ->eachCounted('it.fullcode + "/" + context.Function + "/" + context.Class + "/" + context.Namespace ', 2, '>=');
        $this->prepareQuery();

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->fetchContext()
             ->eachCounted('it.fullcode + "/" + context.Function + "/" + context.Class + "/" + context.Namespace ', 2, '>=');
        $this->prepareQuery();
    }
}

?>
