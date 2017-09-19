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

class Autoload extends Analyzer {
    public function analyze() {
        $this->rowCount       = $this->hasResults();
        $this->processedCount = 1;
        $this->queryCount     = 0;
        $this->rawQueryCount  = 0;

        return true;
    }

    public function toArray() {
        $report = array('composer' => Analyzer::$datastore->getHash('autoload'));

        return $report;
    }

    public function hasResults() {
        $res = Analyzer::$datastore->getHash('autoload');

        $report = $res === 'psr-0' || $res === 'psr-4' ;

        return $report;
    }

    public function getDump() {
        if (!$this->hasResults()) {
            return array();
        }

        return array(
            (object) array('fullcode'  => 'composer.autoload',
                           'file'      => 'composer.json',
                           'line'      => 0,
                           'namespace' => '',
                           'class'     => '',
                           'function'  => '' )
        );
    }
}

?>
