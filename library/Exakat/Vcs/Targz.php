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

class Targz extends Vcs {
    protected function selfCheck(): void {
        $res = shell_exec('tar --version 2>&1') ?? '';
        if (!preg_match('#\d+\.\d+(\.\d+)?#s', $res)) {
            throw new HelperException('Tar');
        }

        $res = shell_exec('gzip -V 2>&1') ?? '';
        if (strpos($res, 'gzip') === false) {
            throw new HelperException('gzip');
        }

        if (ini_get('allow_url_fopen') != true) {
            throw new HelperException('allow_url_fopen');
        }
    }

    public function clone(string $source): void {
        $this->check();

        $binary = file_get_contents($source);
        $archiveFile = tempnam(sys_get_temp_dir(), 'archiveTgz') . '.tar.gz';
        file_put_contents($archiveFile, $binary);

        $res = shell_exec("tar -tzf $archiveFile 2>&1 >/dev/null");
        if (!empty($res)) {
            list($l) = explode("\n", $res, 1);
            print "Error while loading tar.gz archive : \"$l\". Aborting\n";
            return;
        }

        shell_exec("mkdir {$this->destinationFull}; tar -zxf $archiveFile -C {$this->destinationFull}");

        unlink($archiveFile);
    }

    public function getInstallationInfo(): array {
        $stats = array();

        $res = trim(shell_exec('tar --version 2>&1') ?? '');
        if (preg_match('/^(\w+) ([0-9\.]+) /', $res, $r)) {//
            $stats['tar'] = 'Yes';
            $stats['tar version'] = $r[0];
        } else {
            $stats['tar'] = 'No';
            $stats['tar optional'] = 'Yes';
        }

        $res = trim(shell_exec('gzip -V 2>&1') ?? '');
        if (preg_match('/gzip (\d+),/', $res, $r)) {//
            $stats['gzip'] = 'Yes';
            $stats['gzip version'] = $r[1];
        } else {
            $stats['gzip'] = 'No';
            $stats['gzip optional'] = 'Yes';
        }

        return $stats;
    }

    public function getStatus(): array {
        $status = array('vcs'       => 'tar.gz',
                        'updatable' => false
                       );

        return $status;
    }
}

?>