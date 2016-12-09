<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class IsComposerNsname extends Analyzer {
    public function analyze() {
        $data = new Composer();

        $packagistNamespaces = $data->getComposerNamespaces();
        $packagistNamespacesFullNS = $this->makeFullNSPath($packagistNamespaces);

        $packagistClasses = $data->getComposerClasses();
        $packagistClassesFullNS = $this->makeFullNSPath($packagistClasses);
        // Chunks is made to shorten the queries
        $packagistClassesFullNSChunks = array_chunk($packagistClassesFullNS, 5000);

        $packagistInterfaces = $data->getComposerInterfaces();
        $packagistInterfacesFullNs = $this->makeFullNSPath($packagistInterfaces);

        ////////////////////////////////////////////////
        // Use
        // namespaces in Composer
        $this->atomIs('Use')
             ->outIs('USE')
             ->is('originpath', $packagistNamespacesFullNS);
        $this->prepareQuery();

        // classes in Composer
        foreach($packagistClassesFullNSChunks as $id => $p) {
            $this->atomIs('Use')
                 ->outIs('USE')
                 ->is('originpath', $p);
            $this->prepareQuery();
        }

        // interfaces in Composer
        $this->atomIs('Use')
             ->outIs('USE')
             ->is('originpath', $packagistInterfaces);
        $this->prepareQuery();

        // traits in Composer
        $packagistTraits = $data->getComposerTraits();
        $this->atomIs('Use')
             ->outIs('USE')
             ->is('originpath', $packagistTraits);
        $this->prepareQuery();

        ////////////////////////////////////////////////
        // Classes extends or implements
        // Classes in Composer
        foreach($packagistClassesFullNSChunks as $id => $p) {
            $this->atomIs('Class')
                 ->outIs(array('IMPLEMENTS', 'EXTENDS'))
                 ->analyzerIsNot('self')
                 ->fullnspathIs($p);
            $this->prepareQuery();
        }

        $this->atomIs('Class')
             ->outIs(array('IMPLEMENTS', 'EXTENDS'))
             ->fullnspathIs($packagistInterfacesFullNs);
        $this->prepareQuery();

        ////////////////////////////////////////////////
        // Instanceof
        // Classes or interfaces in Composer
        foreach($packagistClassesFullNSChunks as $id => $p) {
            $this->atomIs('Instanceof')
                 ->outIs('CLASS')
                 ->atomIs(array('Nsname', 'Identifier'))
                 ->analyzerIsNot('self')
                 ->fullnspathIs($p);
            $this->prepareQuery();
        }

        $this->atomIs('Instanceof')
             ->outIs('CLASS')
                 ->atomIs(array('Nsname', 'Identifier'))
             ->fullnspathIs($packagistInterfacesFullNs);
        $this->prepareQuery();
    }
}

?>
