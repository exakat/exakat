<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extxmlrpc extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'xmlrpc.ini';

        parent::analyze();
    }
}

?>
