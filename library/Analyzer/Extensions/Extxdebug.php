<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extxdebug extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'xdebug.ini';
        
        parent::analyze();
    }
}

?>
