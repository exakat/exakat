<?php

namespace Analyzer\Structures;

use Analyzer;

class FileUsage extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomFunctionIs(array('fopen', 'file_get_contents', 'file_put_contents'));
        $this->prepareQuery();

        $fileClasses = array('\\SplFileObject', '\\SplTempFileObject', '\\SplFileInfo');

        $this->atomIs('New')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->atomIsNot(array('Variable', 'Array', 'Property', 'Staticproperty', 'Methodcall', 'Staticmethodcall'))
             ->outIs('NEW')
             ->fullnspath($fileClasses);
        $this->prepareQuery();

        $this->atomIs('Staticmethodcall')
             ->outIs('CLASS')
             ->fullnspath($fileClasses);
        $this->prepareQuery();

        $this->atomIs('Staticproperty')
             ->outIs('CLASS')
             ->fullnspath($fileClasses);
        $this->prepareQuery();

        $this->atomIs('Staticconstant')
             ->outIs('CLASS')
             ->fullnspath($fileClasses);
        $this->prepareQuery();
    }
}

?>
