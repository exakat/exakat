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

class IsComposerNsname extends Analyzer {
    public function analyze() {
        $data = new Composer($this->config);

        $packagistNamespaces = $data->getComposerNamespaces();
        $packagistNamespacesFullNS = $this->makeFullNsPath($packagistNamespaces);

        $packagistClasses = $data->getComposerClasses();
        $packagistClassesFullNS = $this->makeFullNsPath($packagistClasses);

        $packagistInterfaces = $data->getComposerInterfaces();
        $packagistInterfacesFullNs = $this->makeFullNsPath($packagistInterfaces);

        $packagistTraits = $data->getComposerTraits();
        $packagistTraitsFullNs = $this->makeFullNsPath($packagistTraits);

        ////////////////////////////////////////////////
        // Use
        // namespaces in Composer
        $this->atomIs('Use')
             ->outIs('USE')
             ->is('originpath', array_merge($packagistNamespacesFullNS, $packagistClassesFullNS, $packagistInterfacesFullNs, $packagistTraitsFullNs));
        $this->prepareQuery();

        ////////////////////////////////////////////////
        // Classes extends or implements
        // Classes in Composer
        $this->atomIs('Class')
             ->outIs(array('IMPLEMENTS', 'EXTENDS'))
             ->fullnspathIs(array_merge($packagistInterfacesFullNs, $packagistClassesFullNS));
        $this->prepareQuery();

        ////////////////////////////////////////////////
        // Instanceof
        // Classes or interfaces in Composer
        $this->atomIs('Instanceof')
             ->outIs('CLASS')
             ->atomIs(array('Nsname', 'Identifier'))
             ->fullnspathIs($packagistInterfacesFullNs);
        $this->prepareQuery();

    }
}

?>
