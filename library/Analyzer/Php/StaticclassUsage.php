<?php

namespace Analyzer\Php;

use Analyzer;

class StaticclassUsage extends Analyzer\Analyzer {
    public $phpVersion = '5.5+';
    
    public function analyze() {
        $this->atomIs("Staticclass");
        $this->prepareQuery();
    }
}

?>
