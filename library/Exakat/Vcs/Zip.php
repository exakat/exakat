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

class Zip extends Vcs {
    public function __construct($destination, $project_root) {
        parent::__construct($destination, $project_root);

        $res = shell_exec('zip --version');
        if (strpos($res, 'Zip') === false) {
            throw new HelperException('zip');
        }

        if (ini_get('allow_url_fopen') != true) {
            throw new HelperException('allow_url_fopen');
        }
    }

    public function clone($source) {
        $binary = file_get_contents($source);
        $archiveFile = tempnam(sys_get_temp_dir(), 'archiveZip').'.zip';
        file_put_contents($archiveFile, $binary);

        shell_exec("unzip $archiveFile -d {$this->destinationFull}/code/");

        unlink($archiveFile);
    }

    public function update() {
        return 'No Update for .zip';
    }

    public function getInstallationInfo() {
        $stats = array();

        $res = shell_exec('zip -v  2>&1');
        if (preg_match('/not found/is', $res)) {
            $stats['installed'] = 'No';
        } elseif (preg_match('/Zip\s+([0-9\.]+)/is', $res, $r)) {
            $stats['installed'] = 'Yes';
            $stats['version'] = $r[1];
        } else {
            $stats['error'] = $res;
        }
        
        return $stats;
    }

    public function getStatus() {
        $status = array('vcs'       => 'zip',
                        'updatable' => false
                       );

        return $status;
    }

}

?>