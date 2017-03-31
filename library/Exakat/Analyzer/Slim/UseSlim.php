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

namespace Exakat\Analyzer\Slim;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Common\ClassUsage;
use Exakat\Analyzer\Common\InterfaceUsage;
use Exakat\Analyzer\Common\TraitUsage;
use Exakat\Data\Slim;

class UseSlim extends Analyzer {
    public function analyze() {
        $data = new Slim($this->config->dir_root.'/data', $this->config->is_phar);

        $analyzerId = null;
        
        $classes    = $data->getClasses();
        if (!empty($classes)) {
            $classes    = array_merge(...array_values($classes));
            $classes    = array_keys(array_count_values($classes));
            $classes    = $this->makeFullNsPath($classes);
    
            if (!empty($classes)) {
                $classesUsage = new ClassUsage($this->gremlin);
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

        $interfaces =  $data->getInterfaces();
        if (!empty($interfaces)) {
            $interfaces = array_merge(...array_values($interfaces));
            $interfaces = array_keys(array_count_values($interfaces));
            $interfaces = $this->makeFullNsPath($interfaces);
        
            if (!empty($interfaces)) {
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

        $traits     =  $data->getTraits();
        if (!empty($traits)) {
            $traits     = array_merge(...array_values($traits));
            $traits     = array_keys(array_count_values($traits));
            $traits     = $this->makeFullNsPath($traits);

            if (!empty($traits)) {
                $traitsUsage = new TraitUsage($this->gremlin);
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
    }
}

?>
