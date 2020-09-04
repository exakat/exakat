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

namespace Exakat\Tasks;

class CleanDb extends Tasks {
    const CONCURENCE = self::ANYTIME;

    protected $logname = self::LOG_NONE;

    public function run(): void {
         if (self::$semaphore === null) {
            $this->manageServer();
        } else {
            fclose(self::$semaphore);
            try {
                $this->manageServer();
            } finally {
                self::$semaphore = @stream_socket_server('udp://0.0.0.0:' . self::$semaphorePort, $errno, $errstr, STREAM_SERVER_BIND);
            }
        }

    }

    private function manageServer(): void {
        if ($this->config->stop === true) {
            display('Stop gremlin server');
            $this->gremlin->stop();
        } elseif ($this->config->start === true) {
            display('Start gremlin server');
            $this->gremlin->start();
        } elseif ($this->config->restart === true) {
            display('Restart gremlin server');
            $this->gremlin->clean();
        } else {
            display('Restart gremlin server');
            $this->gremlin->clean();
        }
    }
}

?>
