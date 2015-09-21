<?php

namespace Analyzer\Composer;

use Analyzer;

class IsComposerClass extends Analyzer\Analyzer {
    public function analyze() {
        $data = new \Data\Composer();

        $classes = $data->getComposerClasses();
        $classesFullNP = $this->makeFullNsPath($classes);
        
        $this->atomIs('Class')
             ->outIs('IMPLEMENTS', 'EXTENDS')
             ->fullnspath($classesFullNP);
        $this->prepareQuery();

        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->fullnspath($classesFullNP);
        $this->prepareQuery();

        $this->atomIs('Typehint')
             ->outIs('CLASS')
             ->fullnspath($classesFullNP);
        $this->prepareQuery();

        $this->atomIs('New')
             ->outIs('NEW')
//             ->atomIs('Nsname')
             ->fullnspath($classesFullNP);
        $this->prepareQuery();
    }
}

?>
