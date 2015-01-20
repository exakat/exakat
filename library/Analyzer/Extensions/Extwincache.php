<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extwincache extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'wincache.ini';
        
        parent::analyze();
    }
}

?>
