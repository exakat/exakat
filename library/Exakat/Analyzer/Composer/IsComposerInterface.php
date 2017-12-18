<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Exakat\Analyzer\Composer;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Composer;

class IsComposerInterface extends Analyzer {

    public function dependsOn() {
        return array('Interfaces/InterfaceUsage');
    }
    
    public function analyze() {
        $data = new Composer($this->config);

        $interfaces = $data->getComposerInterfaces();
        $interfacesFullNP = $this->makeFullNsPath($interfaces);
        
        $this->atomIs('Class')
             ->outIs('IMPLEMENTS', 'EXTENDS')
             ->fullnspathIs($interfacesFullNP);
        $this->prepareQuery();

        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->codeIsNot('array')
             ->fullnspathIs($interfacesFullNP);
        $this->prepareQuery();

        $this->atomIs('Function')
             ->outIs('ARGUMENT')
             ->outIs('TYPEHINT')
             ->fullnspathIs($interfacesFullNP);
        $this->prepareQuery();

        $this->atomIs('Function')
             ->outIs('RETURNTYPE')
             ->fullnspathIs($interfacesFullNP);
        $this->prepareQuery();

        $this->atomIs('Use')
             ->outIs('USE')
             ->fullnspathIs($interfacesFullNP);
        $this->prepareQuery();
    }
}

?>
