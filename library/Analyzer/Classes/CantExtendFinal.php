<?php

namespace Analyzer\Classes;

use Analyzer;

class CantExtendFinal extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Class')
             ->goToAllParents()
             ->hasOut('FINAL')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
