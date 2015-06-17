<?php

namespace Analyzer\Classes;

use Analyzer;

class OnlyStaticMethods extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Class')
             ->outIs('BLOCK')
             ->filter('it.out("ELEMENT").any()') // won't count empty classes as static
             ->filter('it.out("ELEMENT").hasNot("atom", "Function").any() == false')
             ->filter('it.out("ELEMENT").filter{it.out("STATIC").any() == false}.any() == false')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
