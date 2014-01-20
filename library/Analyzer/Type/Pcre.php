<?php

namespace Analyzer\Type;

use Analyzer;

class Pcre extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\String");
    }
    
    function analyze() {
        $this->atomIs('String')
             ->regex('code', '([\'\\"])([\\$#]).*?([\\$#])[imsxeADSUXJu]*[\'\\"]');
        $this->prepareQuery();

        $this->atomIs('String')
             ->regex('code', '([\'\\"])\\\\/[^\\\\/]*?\\\\/[imsxeADSUXJu]*[\'\\"]');
        $this->prepareQuery();

        $this->atomIs('String')
             ->regex('code', '([\'\\"])\\\\{.*?\\\\}[imsxeADSUXJu]*[\'\\"]');
        $this->prepareQuery();
    }
}

?>