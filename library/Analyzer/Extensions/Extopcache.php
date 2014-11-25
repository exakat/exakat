<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extopcache extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'opcache.ini';
        
        parent::analyze();
    }
}

?>