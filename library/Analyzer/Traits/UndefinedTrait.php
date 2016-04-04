<?php

namespace Analyzer\Traits;

use Analyzer;

class UndefinedTrait extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Use')
             ->filter(' it.in("ELEMENT").in("BLOCK").filter{ it.atom in ["Trait", "Class"]}.any()')
             ->outIs('USE')
             ->noTraitDefinition();
        $this->prepareQuery();
    }
}

?>
