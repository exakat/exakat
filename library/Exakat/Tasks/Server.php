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

class Server extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run(): void {
        if ($this->config->stop    === true ||
            $this->config->restart === true
            ) {
            $display = @file_get_contents('http://localhost:7447/stop/');
            if (empty($display)) {
                $display = 'No server found';
            }
            display("Shut down server ($display)");

            if ($this->config->stop === true) {
                return;
            }
        }

        if (file_exists("{$this->config->dir_root}/projects/index.php")) {
            display('A server is already installed. Aborting.');
            return;
        }

        display('Copy router server');
        $php = file_get_contents("{$this->config->dir_root}/server/index.php");
        $php = str_replace('__PHP__', $this->config->php, $php);
        $php = str_replace('__EXAKAT__', $this->config->executable, $php);
        file_put_contents("{$this->config->projects_root}/projects/index.php", $php);

        if (!file_exists("{$this->config->projects_root}/projects/server.log")) {
            file_put_contents("{$this->config->projects_root}/projects/server.log", date('r') . "\tCreated file\n");
        }

        display('Start server');
        exec($this->config->php . ' -S 0.0.0.0:7447 -t ' . $this->config->projects_root . '/projects/ ' . $this->config->projects_root . '/projects/index.php > /dev/null 2 > /dev/null &');
        display('Started server');
    }
}

?>
