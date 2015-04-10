<?php

namespace Analyzer\Php;

use Analyzer;

class ReservedKeywords7 extends Analyzer\Analyzer {
    protected $phpVersion = '7.0-';
    
    public function analyze() {
        $keywords = array('int', 'float', 'bool', 'string', 'true', 'false', 'null');
        
        $this->atomIs('Class')
             ->outIs('NAME')
             ->code($keywords)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Trait')
             ->outIs('NAME')
             ->code($keywords)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Interface')
             ->outIs('NAME')
             ->code($keywords)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Nsname')
             ->outIs('SUBNAME')
             ->code($keywords)
             ->back('first');
        $this->prepareQuery();
    }
}

?>
