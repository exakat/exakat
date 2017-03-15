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

namespace Exakat\Analyzer\Cakephp;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Common\ClassUsage;
use Exakat\Analyzer\Common\InterfaceUsage;
use Exakat\Analyzer\Common\TraitUsage;
use Exakat\Data\CakePHP;

class CakePHPUsed extends Analyzer {
    protected $component  = null;
    protected $version    = null;
    
    public function analyze() {
        $data = new CakePHP($this->config->dir_root.'/data', $this->config->is_phar);

        $this->init();
        
        $classes    = $data->getClasses($this->component, $this->version);
        if (!empty($classes)) {
            $classes    = array_merge(...array_values($classes));
            $classes    = array_keys(array_count_values($classes));
            $classes    = $this->makeFullNsPath($classes);

            if (!empty($classes)) {
                $classesUsage = new ClassUsage($this->gremlin);
                $classesUsage->setAnalyzer(get_class($this));
                $classesUsage->setClasses($classes);
                $classesUsage->init($this->analyzerId);
                $classesUsage->run();

                $this->rowCount        += $classesUsage->getRowCount();
                $this->processedCount  += $classesUsage->getProcessedCount();
                $this->queryCount      += $classesUsage->getQueryCount();
                $this->rawQueryCount   += $classesUsage->getRawQueryCount();
            }
        }

        $interfaces =  $data->getInterfaces($this->component, $this->version);
        if (!empty($interfaces)) {
            $interfaces = array_merge(...array_values($interfaces));
            $interfaces = array_keys(array_count_values($interfaces));
            $interfaces = $this->makeFullNsPath($interfaces);
        
            if (!empty($interfaces)) {
                $classesUsage = new InterfaceUsage($this->gremlin);
                $classesUsage->setAnalyzer(get_class($this));
                $classesUsage->setInterfaces($interfaces);
                $classesUsage->init($this->analyzerId);
                $classesUsage->run();

                $this->rowCount        += $classesUsage->getRowCount();
                $this->processedCount  += $classesUsage->getProcessedCount();
                $this->queryCount      += $classesUsage->getQueryCount();
                $this->rawQueryCount   += $classesUsage->getRawQueryCount();
            }
        }

        $traits     =  $data->getTraits($this->component, $this->version);
        if (!empty($traits)) {
            $traits     = array_merge(...array_values($traits));
            $traits     = array_keys(array_count_values($traits));
            $traits     = $this->makeFullNsPath($traits);

            if (!empty($traits)) {
                $classesUsage = new TraitUsage($this->gremlin);
                $classesUsage->setAnalyzer(get_class($this));
                $classesUsage->setTraits($traits);
                $classesUsage->init($this->analyzerId);
                $classesUsage->run();

                $this->rowCount        += $classesUsage->getRowCount();
                $this->processedCount  += $classesUsage->getProcessedCount();
                $this->queryCount      += $classesUsage->getQueryCount();
                $this->rawQueryCount   += $classesUsage->getRawQueryCount();
            }
        }
    }
}

?>
