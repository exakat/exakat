<?php

namespace Analyzer\Type;

use Analyzer;

class Md5String extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('String');
    }
    
    public function analyze() {
        $this->atomIs('String')
             ->regex('code', '^[\'\\"]?[0-9A-Fa-f]{32}[\'\\"]?\\$');
        $this->prepareQuery();
    }
}

?>
