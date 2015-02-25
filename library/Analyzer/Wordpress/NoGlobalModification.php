<?php

namespace Analyzer\Wordpress;

use Analyzer;

class NoGlobalModification extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsModified');
    }

    public function analyze() {
        $globalNames = $this->loadIni('wp_globals.ini', 'globals');
        
        $this->atomIs('Global')
             ->outIs('GLOBAL')
             ->atomIs('Variable')
             ->code($globalNames)
             ->savePropertyAs('code', 'name')
             ->goToFunction()
             ->outIs('BLOCK')
             ->atomInside('Variable')
             ->samePropertyAs('code', 'name')
             ->analyzerIs('Analyzer\\Variables\\IsModified')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
