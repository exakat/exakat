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

namespace Exakat\Analyzer\ZendF;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Common\ClassUsage;
use Exakat\Analyzer\Common\InterfaceUsage;
use Exakat\Analyzer\Common\TraitUsage;
use Exakat\Data\ZendF3;

class Zf3Component extends Analyzer {
    protected $component  = null;
    protected $version    = null;
    
    public function analyze() {
        $data = new ZendF3($this->config->dir_root.'/data', $this->config);
        if (empty($this->component)) {
            return;
        }

        $analyzerId = null;

        $classes    = $data->getClasses($this->component, $this->version);
        if (!empty($classes)) {
            $classes    = call_user_func_array('array_merge', array_values($classes));
            $classes    = array_keys(array_count_values($classes));
            $classes    = $this->makeFullNsPath($classes);
    
            if (!empty($classes)) {
                $classesUsage = new ClassUsage($this->gremlin, $this->config);
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

        $interfaces =  $data->getInterfaces($this->component, $this->version);
        if (!empty($interfaces)) {
            $interfaces = call_user_func_array('array_merge', array_values($interfaces));
            $interfaces = array_keys(array_count_values($interfaces));
            $interfaces = $this->makeFullNsPath($interfaces);
        
            if (!empty($interfaces)) {
                $interfacesUsage = new InterfaceUsage($this->gremlin, $this->config);
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

        $traits     =  $data->getTraits($this->component, $this->version);
        if (!empty($traits)) {
            $traits     = call_user_func_array('array_merge', array_values($traits));
            $traits     = array_keys(array_count_values($traits));
            $traits     = $this->makeFullNsPath($traits);

            if (!empty($traits)) {
                $traitsUsage = new TraitUsage($this->gremlin, $this->config);
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
