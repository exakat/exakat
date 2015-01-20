<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extxcache extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'xcache.ini';
        
        parent::analyze();
    }
}

?>
