<?php

namespace Analyzer\Security;

use Analyzer;

class NoSleep extends Analyzer\Analyzer {
    public function analyze() {
        // simple call to usleep 
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\sleep', '\\usleep'));
        $this->prepareQuery();
    }
}

?>
