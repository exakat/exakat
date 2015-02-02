<?php

namespace Analyzer\Structures;

use Analyzer;

class FileUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomFunctionIs(array('fopen', 'file_get_contents', 'file_put_contents'));
        $this->prepareQuery();
        
        $this->atomIs('New')
             ->outis('NEW')
             ->fullnspath(array('\\SplFileObject', '\\SplTempFileObject', '\\SplFileInfo'));
        $this->prepareQuery();
    }
}

?>
