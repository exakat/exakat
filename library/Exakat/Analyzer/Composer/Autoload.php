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


namespace Exakat\Analyzer\Composer;

use Exakat\Analyzer\Analyzer;

class Autoload extends Analyzer {
    public function analyze(): void {
        $this->rowCount       = (int) $this->hasResults();
        $this->processedCount = 1;
        $this->queryCount     = 0;
        $this->rawQueryCount  = 0;
    }

    public function toArray(): array {
        $report = array('composer' => $this->datastore->getHash('autoload'));

        return $report;
    }

    public function hasResults(): bool {
        $res = $this->datastore->getHash('autoload');

        $report = $res === 'psr-0' || $res === 'psr-4' ;

        return $report;
    }

    public function getDump(): array {
        if (!$this->hasResults()) {
            return array();
        }

        return array(
                array('fullcode'  => 'composer.autoload',
                     'file'      => 'composer.json',
                     'line'      => 0,
                     'namespace' => '',
                     'class'     => '',
                     'function'  => '',
                    )
                );
    }
}

?>
