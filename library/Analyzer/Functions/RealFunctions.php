<?php

namespace Analyzer\Functions;

use Analyzer;

class RealFunctions extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Function')
             ->filter('it.out("NAME").has("atom", "String").any() == false')
             ->filter('it.in("ELEMENT").in("BLOCK").filter{ it.atom in ["Class", "Trait", "Interface"] }.any() == false')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
