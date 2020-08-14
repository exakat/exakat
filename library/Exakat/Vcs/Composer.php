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

class Composer extends Vcs {
    private $executable = 'composer';

    public function __construct($destination, $project_root) {
        parent::__construct($destination, $project_root);
    }

    protected function selfCheck() {
        $res = shell_exec("{$this->executable} --version 2>&1");
        if (strpos($res, 'Composer') === false) {
            throw new HelperException('Composer');
        }
    }

    public function clone(string $source): void {
        $this->check();

        // composer install
        $composer = new \stdClass();
        $composer->{'minimum-stability'} = 'dev';
        $composer->require = new \stdClass();
        $composer->require->$source = 'dev-master';
        $json = json_encode($composer, JSON_PRETTY_PRINT);

        mkdir("{$this->destinationFull}", 0755);
        file_put_contents("{$this->destinationFull}/composer.json", $json);
        shell_exec("cd {$this->destinationFull}; {$this->executable} -q install");
    }

    public function update() {
        $this->check();

        shell_exec("cd {$this->destinationFull}; {$this->executable} -q update");

        $composerPath = "{$this->destinationFull}/composer.json";
        if (!file_exists($composerPath)) {
            return '';
        }

        $jsonText = file_get_contents($composerPath);
        if (empty($jsonText)) {
            return '';
        }
        $json = json_decode($jsonText);
        $component = array_keys( (array) $json->require)[0];

        $jsonLockText = file_get_contents("{$this->destinationFull}/composer.lock");
        if (empty($jsonLockText)) {
            return $jsonLockText;
        }
        $jsonLock = json_decode($jsonLockText);

        $return = '';
        foreach ($jsonLock->packages as $package) {
            if ($package->name === $component) {
                $return = "{$package->source->reference} (version : {$package->version})";
            }
        }

        return $return;
    }

    public function getInstallationInfo() {
        $stats = array();

        $res = trim(shell_exec("{$this->executable} -V 2>&1"));
        // remove colors from shell syntax
        $res = preg_replace('/\e\[[\d;]*m/', '', $res);
        if (preg_match('/Composer version ([0-9\.a-z@_\(\)\-]+) /', $res, $r)) {//
            $stats['installed'] = 'Yes';
            $stats['version'] = $r[1];
        } else {
            $stats['installed'] = 'No';
        }

        return $stats;
    }

    public function getStatus(): array {
        $composerLockPath = "{$this->destinationFull}/composer.lock";
        if (!file_exists($composerLockPath)) {
            $status = array( 'vcs'       => 'composer',
                             'updatable' => true,
                             'hash'      => 'No composer.lock',
                             );

            return $status;
        }

        $status = array( 'vcs'       => 'composer',
                         'updatable' => true,
                         );
        $composerLock = file_get_contents($composerLockPath);

        $json = json_decode($composerLock);
        if (isset($json->hash)) {
            $status['hash'] = $json->hash;
        } else {
            $status['hash'] = 'Can\'t read hash';
        }

        return $status;
    }
}

?>