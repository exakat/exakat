<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Composer;

use Analyzer;

class IsComposerNsname extends Analyzer\Analyzer {
    public function analyze() {
        $data = new \Data\Composer();

        ////////////////////////////////////////////////
        // Use
        // namespaces in Composer
        $packagistNamespaces = $data->getComposerNamespaces();
        $this->atomIs('Use')
             ->outIs('USE')
             ->is('originpath', $packagistNamespaces);
        $this->prepareQuery();

        // classes in Composer
        $packagistClasses = $data->getComposerClasses();
        $this->atomIs('Use')
             ->outIs('USE')
             ->is('originpath', $packagistClasses);
        $this->prepareQuery();

        // interfaces in Composer
        $packagistInterfaces = $data->getComposerInterfaces();
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
        $packagistClasses = $this->makeFullNSpath($packagistClasses);
        $this->atomIs('Class')
             ->outIs(array('IMPLEMENTS', 'EXTENDS'))
             ->is('fullnspath', $packagistClasses);
        $this->prepareQuery();

        $packagistInterfaces = $this->makeFullNSpath($packagistInterfaces);
        $this->atomIs('Class')
             ->outIs(array('IMPLEMENTS', 'EXTENDS'))
             ->is('fullnspath', $packagistInterfaces);
        $this->prepareQuery();
    }
}

?>
