<?php

namespace Analyzer\Type;

use Analyzer;

class Pcre extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\String");
    }
    
    function analyze() {
        // regex like $....$is
        $this->atomIs('String')
             ->regex('code', '([\'\\"])\\$[^\\$].*?\\$[imsxeADSUXJu]*[\'\\"]');
        $this->prepareQuery();

        // regex like #....#is
        $this->atomIs('String')
             ->regex('code', '([\'\\"])#[^#].*?#[imsxeADSUXJu]*[\'\\"]');
        $this->prepareQuery();

        // regex like ~....~is
        $this->atomIs('String')
             ->regex('code', '([\'\\"])~[^~].*?~[imsxeADSUXJu]*[\'\\"]');
        $this->prepareQuery();

        // regex like /..../
        $this->atomIs('String')
             ->regex('code', '([\'\\"])\\\\/[^\\\\/*][^\\\\/]*?\\\\/[imsxeADSUXJu]*[\'\\"]');
        $this->prepareQuery();

        // regex like {....}
        $this->atomIs('String')
             ->regex('code', '([\'\\"])\\\\{.+?\\\\}[imsxeADSUXJu]*[\'\\"]');
        $this->prepareQuery();
    }
}

?>