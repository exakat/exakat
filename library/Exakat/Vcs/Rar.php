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

namespace Exakat\Vcs;

use Exakat\Exceptions\HelperException;

class Rar extends Vcs {
    private $executable = 'unrar';

    public function __construct($destination, $project_root) {
        parent::__construct($destination, $project_root);
    }

    protected function selfCheck() {
        $res = shell_exec("{$this->executable} 2>&1");
        if (strpos($res, 'UNRAR') === false) {
            throw new HelperException('rar');
        }

        if (ini_get('allow_url_fopen') != true) {
            throw new HelperException('allow_url_fopen');
        }
    }

    public function clone(string $source): void {
        $this->check();

        $binary = file_get_contents($source);
        $archiveFile = tempnam(sys_get_temp_dir(), 'archiveRar') . '.rar';
        file_put_contents($archiveFile, $binary);

        shell_exec("{$this->executable} x $archiveFile {$this->destinationFull}");

        unlink($archiveFile);
    }

    public function getInstallationInfo() {
        $stats = array();

        $res = shell_exec("{$this->executable} 2>&1");
        if (stripos($res, 'not found') !== false) {
            $stats['installed'] = 'No';
        } elseif (preg_match('/UNRAR\s+([0-9\.]+)/is', $res, $r)) {
            $stats['installed'] = 'Yes';
            $stats['version'] = $r[1];
        } else {
            $stats['error'] = $res;
        }

        return $stats;
    }

    public function getStatus(): array {
        $status = array('vcs'       => 'rar',
                        'updatable' => false
                       );

        return $status;
    }
}

?>