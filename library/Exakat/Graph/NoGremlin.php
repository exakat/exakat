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

namespace Exakat\Graph;

use Exakat\Graph\Helpers\GraphResults;

class NoGremlin extends Graph {
    public function query(string $query, array $params = array(),array $load = array()): GraphResults {
        return new GraphResults();
    }

    public function queryOne(string $query, array $params = array(),array $load = array()): GraphResults {
        return new GraphResults();
    }

    public function init(): void {
    }

    public function start(): void {
    }

    public function stop(): void {
    }

    public function serverInfo(): array {
        return array('Server' => 'None');
    }

    public function checkConnection(): bool {
        return true;
    }

    public function clean(): void {
    }

    public function getDefinitionSQL(): string {
        return 'PRAGMA no_sql;';
    }

    public function getInfo(): array {
        return array('installed' => 'Always',
                    );
    }

}

?>
