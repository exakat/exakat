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

namespace Exakat\Analyzer\Psr;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Common\ClassUsage;
use Exakat\Analyzer\Common\InterfaceUsage;
use Exakat\Analyzer\Common\TraitUsage;

class Psr16Usage extends Analyzer {
    public function analyze() {
        $info = $this->loadJson('psr/psr-16.json');

        $analyzerId = null;

        // Using the defined interfaces
        $interfaces = array();
        foreach($info->interfaces as $interface) {
            $interfaces[] = $interface->namespace.'\\'.$interface->name;
        }
        if (!empty($interfaces)) {
            $interfaces = $this->makeFullNsPath($interfaces);

            $interfacesUsage = new InterfaceUsage($this->gremlin);
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
}

?>
