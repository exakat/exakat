<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extxmlwriter extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'xmlwriter.ini';

        parent::analyze();
    }
}

?>