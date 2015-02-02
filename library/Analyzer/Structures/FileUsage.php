<?php

namespace Analyzer\Structures;

use Analyzer;

class FileUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomFunctionIs(array('fopen', 'file_get_contents', 'file_put_contents'));
        $this->prepareQuery();

        $fileClasses = array('\\SplFileObject', '\\SplTempFileObject', '\\SplFileInfo');

        $this->atomIs('New')
             ->outis('NEW')
             ->fullnspath($fileClasses);
        $this->prepareQuery();

        $this->atomIs('Staticmethodcall')
             ->outis('CLASS')
             ->fullnspath($fileClasses);
        $this->prepareQuery();

        $this->atomIs('Staticproperty')
             ->outis('CLASS')
             ->fullnspath($fileClasses);
        $this->prepareQuery();

        $this->atomIs('Staticconstant')
             ->outis('CLASS')
             ->fullnspath($fileClasses);
        $this->prepareQuery();
    }
}

?>
