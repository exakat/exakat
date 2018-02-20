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

class Tarbz extends Vcs {
    public function __construct($destination, $project_root) {
        parent::__construct($destination, $project_root);

        $res = shell_exec('tar --version');
        if (!preg_match('#\d+\.\d+\.\d+#s', $res)) {
            throw new HelperException('Tar');
        }

        $res = shell_exec('bzip2 -V');
        if (strpos($res, 'bzip2') === false) {
            throw new HelperException('bzip2');
        }
        
        if (ini_get('allow_url_fopen') != true) {
            throw new HelperException('allow_url_fopen');
        }
    }

    public function clone($source) {
        $binary = file_get_contents($source);
        $archiveFile = tempnam(sys_get_temp_dir(), 'archiveTgz').'.tar.bz2';
        file_put_contents($archiveFile, $binary);

        shell_exec("mkdir {$this->destinationFull}/code/; tar -jxf $archiveFile --directory $this->destinationFull/code");

        unlink($archiveFile);
    }

    public function update() {
        return 'No Update for Tar.bz2';
    }

}

?>