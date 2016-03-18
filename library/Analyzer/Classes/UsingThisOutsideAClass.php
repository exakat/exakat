<?php

namespace Analyzer\Classes;

use Analyzer;

class UsingThisOutsideAClass extends Analyzer\Analyzer {
    public function analyze() {
        // $this outside a class or a trait
        $this->atomIs('Variable')
             ->code('$this')
             ->hasNoClass()
             ->hasNoTrait();
        $this->prepareQuery();
    }
}

?>
