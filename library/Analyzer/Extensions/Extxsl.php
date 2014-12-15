<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extxsl extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'xsl.ini';

        parent::analyze();
    }
}

?>
