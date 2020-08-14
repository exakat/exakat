<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Common;

use Exakat\Analyzer\Analyzer;

class UsesFramework extends Analyzer {
    protected $classes    = array();
    protected $interfaces = array();
    protected $traits     = array();
    protected $namespaces = array();

    public function analyze(): void {
        $analyzerId = null;

        if (!empty($this->classes[0])) {
            $classes    = makeFullNsPath($this->classes);

            if (!empty($classes)) {
                $classesUsage = new ClassUsage();
                $classesUsage->setAnalyzer(get_class($this));
                $classesUsage->setClasses($classes);
                $analyzerId = $classesUsage->init($analyzerId);
                $classesUsage->run();

                $this->rowCount        += $classesUsage->getRowCount();
                $this->processedCount  += $classesUsage->getProcessedCount();
                $this->queryCount      += $classesUsage->getQueryCount();
                $this->rawQueryCount   += $classesUsage->getRawQueryCount();
            }
        }

        if (!empty($this->interfaces[0])) {
            $interfaces = makeFullNsPath($this->interfaces);

            if (!empty($interfaces)) {
                $interfacesUsage = new InterfaceUsage();
                $interfacesUsage->setAnalyzer(get_class($this));
                $interfacesUsage->setInterfaces($interfaces);
                $analyzerId = $interfacesUsage->init($analyzerId);
                $interfacesUsage->run();

                $this->rowCount        += $interfacesUsage->getRowCount();
                $this->processedCount  += $interfacesUsage->getProcessedCount();
                $this->queryCount      += $interfacesUsage->getQueryCount();
                $this->rawQueryCount   += $interfacesUsage->getRawQueryCount();
            }
        }

        if (!empty($this->traits[0])) {
            $traits     = makeFullNsPath($this->traits);

            if (!empty($traits)) {
                $traitsUsage = new TraitUsage();
                $traitsUsage->setAnalyzer(get_class($this));
                $traitsUsage->setTraits($traits);
                $analyzerId = $traitsUsage->init($analyzerId);
                $traitsUsage->run();

                $this->rowCount        += $traitsUsage->getRowCount();
                $this->processedCount  += $traitsUsage->getProcessedCount();
                $this->queryCount      += $traitsUsage->getQueryCount();
                $this->rawQueryCount   += $traitsUsage->getRawQueryCount();
            }
        }

        if (!empty($this->namespaces[0])) {
            $namespaces     = makeFullNsPath($this->namespaces);

            if (!empty($namespaces)) {
                $namespacesUsage = new NamespaceUsage();
                $namespacesUsage->setAnalyzer(get_class($this));
                $namespacesUsage->setNamespaces($namespaces);
                $analyzerId = $namespacesUsage->init($analyzerId);
                $namespacesUsage->run();

                $this->rowCount        += $namespacesUsage->getRowCount();
                $this->processedCount  += $namespacesUsage->getProcessedCount();
                $this->queryCount      += $namespacesUsage->getQueryCount();
                $this->rawQueryCount   += $namespacesUsage->getRawQueryCount();
            }
        }
    }
}

?>
