<?php

namespace Analyzer\Classes;

use Analyzer;

class OldStyleVar extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Class")
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->tokenIs('T_VAR')
             ;
        $this->prepareQuery();
    }
}

?>
