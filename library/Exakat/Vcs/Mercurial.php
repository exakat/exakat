<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Vcs;

use Exakat\Exceptions\HelperException;

class Mercurial extends Vcs {
    public function __construct($destination, $project_root) {
        parent::__construct($destination, $project_root);

        $res = shell_exec('hg --version');
        if (strpos($res, 'Mercurial') === false) {
            throw new HelperException('Mercurial');
        }
    }

    public function clone($source) {
        $source = escapeshellarg($URL);
        shell_exec("cd {$this->destinationFull}; hg clone $source code");
    }

    public function update() {
        $res = shell_exec("cd {$this->destinationFull}/code/; hg pull 2>&1; hg update; hg log -l 1");
        preg_match('/changeset:\s+(\S+)/', $res, $changeset);
        preg_match("/date:\s+([^\n]+)/", $res, $date);

        return "$changeset[1] ($date[1])";
    }

}

?>