<?php

namespace Analyzer\Classes;

use Analyzer;

class UnusedClass extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Classes\\UsedClass");
    }

    public function analyze() {
        // class used in a New
        $this->atomIs("Class")
             ->analyzerIsNot("Analyzer\\Classes\\UsedClass");
        $this->prepareQuery();
    }
}

?>