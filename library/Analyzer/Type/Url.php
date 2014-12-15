<?php

namespace Analyzer\Type;

use Analyzer;

class Url extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Type\\String");
    }
    
    public function analyze() {
        $this->atomIs('String')
             ->regex('code', '^.?([a-z]+)://[-\\\\p{L}0-9+&@#/%?=~_|!:,.;]*[-a-zA-Z0-9+&@#/%=~_|].?\\$');
        $this->prepareQuery();
    }
}

?>
