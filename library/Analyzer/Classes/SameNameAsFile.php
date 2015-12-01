<?php

namespace Analyzer\Classes;

use Analyzer;

class SameNameAsFile extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs(array('Class', 'Interface', 'Trait'))
             ->outIs('NAME')
             ->savePropertyAs('code', 'classname')
             ->goToFile()
             // Is the clasname also the filename (case insensitive)
             ->regex('filename', '(?i)" + classname + "\\\\.php\\$')
             // Is the clasname also the filename (case sensitive)
             ->regexNot('filename', '" + classname + "\\\\.php\\$')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
