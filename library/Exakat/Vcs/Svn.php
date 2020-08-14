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

class Svn extends Vcs {
    private $info = array();
    private $executable = 'svn';

    public function __construct($destination, $project_root) {
        parent::__construct($destination, $project_root);
    }

    protected function selfCheck() {
        $res = shell_exec("{$this->executable} --version 2>&1") ?? '';
        if (strpos($res, 'svn') === false) {
            throw new HelperException('SVN');
        }
    }

    public function clone(string $source): void {
        $this->check();

        $source = escapeshellarg($source);
        $codePath = dirname($this->destinationFull);
        shell_exec("cd {$codePath}; {$this->executable} checkout --quiet $source code");
    }

    public function update() {
        $this->check();

        $res = shell_exec("cd {$this->destinationFull}; {$this->executable} update") ?? '';
        if (preg_match('/Updated to revision (\d+)\./', $res, $r)) {
            return $r[1];
        }

        if (preg_match('/At revision (\d+)/', $res, $r)) {
            return $r[1];
        }

        return 'Error : ' . $res;
    }

    private function getInfo() {
        $res = trim(shell_exec("cd {$this->destinationFull}; {$this->executable} info") ?? '');

        if (empty($res)) {
            $this->info['svn'] = '';

            return;
        }
        foreach (explode("\n", $res) as $info) {
            list($name, $value) = explode(': ', trim($info));
            $this->info[$name] = $value;
        }
    }

    public function getBranch() {
        if (empty($this->info)) {
            $this->getInfo();
        }

        return $this->info['Relative URL'] ?? 'trunk';
    }

    public function getRevision() {
        if (empty($this->info)) {
            $this->getInfo();
        }

        return $this->info['Revision'] ?? 'No Revision';
    }

    public function getInstallationInfo() {
        $stats = array();

        $res = trim(shell_exec("{$this->executable} --version 2>&1") ?? '');
        if (preg_match('/svn, version ([0-9\.]+) /', $res, $r)) {//
            $stats['installed'] = 'Yes';
            $stats['version'] = $r[1];
        } else {
            $stats['installed'] = 'No';
            $stats['optional'] = 'Yes';
        }

        return $stats;
    }

    public function getStatus(): array {
        $status = array('vcs'       => 'svn',
                        'revision'  => $this->getRevision(),
                        'updatable' => false
                       );

        return $status;
    }

    public function getDiffLines($r1, $r2): array {
        display("No support for line diff in SVN.\n");
        return array();
    }

    public function getLastCommitDate(): int {
        $res = trim(shell_exec("cd {$this->destinationFull}; {$this->executable} info 2>&1") ?? '');

        //Last Changed Date: 2020-07-22 09:17:27 +0200 (Wed, 22 Jul 2020)
        if (preg_match('/Last Changed Date: (\d{4}.+\d{4}) /m', $res, $r)) {
            return strtotime($r[1]);
        } else {
            return 0;
        }
    }
}

?>