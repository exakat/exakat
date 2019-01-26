<?php
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

namespace Exakat\Analyzer\Ext;

use Exakat\Analyzer\Analyzer;

class DefinedClasses extends Analyzer {
    private $analyzerList = array();

    public function analyze() {
        foreach($this->getAnalyzerList() as $analyzer) {
            $classesUsage = $this->themes->getInstance($analyzer, $this->gremlin, $this->config);
            $classesUsage->run();
    
            $this->rowCount        += $classesUsage->getRowCount();
            $this->processedCount  += $classesUsage->getProcessedCount();
            $this->queryCount      += $classesUsage->getQueryCount();
            $this->rawQueryCount   += $classesUsage->getRawQueryCount();
        }
    }
    
    public function getAnalyzerList() {
        foreach($this->config->ext->getPharList() as $phar) {
            $ext = basename($phar, '.phar');
            $this->analyzerList[] = "{$ext}/{$ext}Usage";
        }
        
        return $this->analyzerList;
    }

}

?>
