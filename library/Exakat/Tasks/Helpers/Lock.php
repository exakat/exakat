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

namespace Exakat\Tasks\Helpers;

class Lock {
    private $path = null;

    public function __construct(string $path, string $name) {
        $b = array_reverse(explode('/', $name));

        $name = $b[1] . '-' . $b[0];
        $this->path = $path . '/' . $name;
    }

    public function check(): bool {
        $fp = @fopen($this->path, 'x');
        if ($fp === false) {
            $this->path = null;
            return false;
        }
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            fwrite($fp, (string) getmypid());
            return true;
        } else {
            $this->path = null;
            return false;
        }
    }

    public function __destruct() {
        if (!empty($this->path) && file_exists($this->path)) {
            $pid = file_get_contents($this->path);

            assert($pid == getmypid(), "Alert : removing wrong pid for $this->path");

            unlink($this->path);
        }
    }
}

?>
