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

class Api extends Tasks {
    const CONCURENCE = self::ANYTIME;

    const PORT = 8447;

    public function run(): void {
        if ($this->config->stop    === true ||
            $this->config->restart === true
            ) {
            $display = @file_get_contents('http://localhost:' . self::PORT . '/?json=["stop"]');
            if (empty($display)) {
                $display = 'No server found';
            }
            display('Shut down server (' . $display . ')');

            if ($this->config->stop === true) {
                return;
            }
        }

        if (file_exists($this->config->dir_root . '/projects/api.php')) {
            display('An API is already installed. Aborting.');
            return;
        }

        display('Copy router server');
        $php = file_get_contents($this->config->dir_root . '/server/api.php');

        $tags = array('__PHP__', '__EXAKAT__', '__SECRET_KEY__');
        $values = array($this->config->php, $this->config->executable, $this->config->transit_key);
        $php = str_replace($tags, $values, $php);

        file_put_contents("{$this->config->projects_root}/projects/api.php", $php);

        if (!file_exists("{$this->config->projects_root}/projects/api.log")) {
            file_put_contents("{$this->config->projects_root}/projects/api.log", date('r') . "\tCreated file\n");
        }

        display('Start api');
        exec($this->config->php . ' -S 0.0.0.0:' . self::PORT . ' -t ' . $this->config->projects_root . '/projects/ ' . $this->config->projects_root . '/projects/api.php > /dev/null 2 > /dev/null &');
        display('Started api');
    }
}

?>
