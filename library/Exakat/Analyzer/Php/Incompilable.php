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


namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Analyzer;

class Incompilable extends Analyzer {
    public function analyze(): void {
        $r = $this->datastore->getRow('compilation' . $this->config->phpversion[0] . $this->config->phpversion[2]);

        $this->rowCount       = count($r);
        $this->processedCount = 1;
        $this->queryCount     = 0;
        $this->rawQueryCount  = 0;
    }

    public function toArray(): array {
        $report = array();

        foreach($this->config->other_php_versions as $version) {
            $r = $this->datastore->getRow('compilation' . $version);

            foreach($r as $l) {
                $l['version'] = $version;
                $report[] = $l;
            }
        }

        return $report;
    }

    public function getDump(): array {
        if (!$this->hasResults()) {
            return array();
        }

        $report = array();
        // Collect version from datastore
        $r = $this->datastore->getHash('php_version');
        $version = $r[0] . $r[2];
        $r = $this->datastore->getRow('compilation' . $version);
        $report = array();

        foreach($r as $l) {
            $l['fullcode']  = $l['error'];
            $l['code']      = $l['error'];
            $l['namespace'] = '';
            $l['class']     = '';
            $l['function']  = '';

            $report[] = $l;
        }

        return $report;
    }

    public function hasResults(): bool {
        foreach($this->config->other_php_versions as $version) {
            $r = $this->datastore->getRow('compilation' . $version);

            if (!empty($r)) {
                return true;
            }
        }
        return false;
    }
}

?>
