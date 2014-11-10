<?php

namespace Analyzer\Namespaces;

use Analyzer;

class UseWithFullyQualifiedNS extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Use")
             ->outIs('USE')
             ->atomIs(array('Nsname', 'As'))
             ->is('absolutens', "true");
        $this->prepareQuery();
    }
}

?>