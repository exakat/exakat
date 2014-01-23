<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extbzip2 extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'bzip2.ini';
        
        parent::analyze();
    }
}

?>