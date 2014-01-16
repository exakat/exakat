<?php

namespace Analyzer\Type;

use Analyzer;

class Heredoc extends Analyzer\Analyzer {
    function dependsOn() {
        return array("Analyzer\\Type\\String");
    }
    
    function analyze() {
        $this->atomIs('String')
             ->tokenIs('T_START_HEREDOC')
             ->filter('it.code.substring(0, 4) != "<<<\'"');
    }
}

?>