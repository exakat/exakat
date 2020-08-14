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

class UseComposerLock extends Analyzer {
    public function analyze(): void {
        $this->rowCount       = (int) $this->datastore->getHash('composer.lock');
        $this->processedCount = 1;
        $this->queryCount     = 0;
        $this->rawQueryCount  = 0;
    }

    public function toArray(): array {
        $report = array('composer.lock' => $this->datastore->getHash('composer.lock'));

        return $report;
    }

    public function hasResults(): bool {
        $report = $this->datastore->getHash('composer.lock') === 1;

        return $report;
    }

    public function getDump(): array {
        if ($this->hasResults()) {
            return array();
        }

        return array(
               array('fullcode'  => 'composer.lock',
                     'file'      => 'composer.lock',
                     'line'      => 0,
                     'namespace' => '',
                     'class'     => '',
                     'function'  => '',
                    )
                );
    }

}

?>
