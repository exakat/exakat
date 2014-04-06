<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extxmlreader extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'xmlreader.ini';

        parent::analyze();
    }
}

?>