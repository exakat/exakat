<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extlibxml extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'libxml.ini';
        
        parent::analyze();
    }
}

?>
