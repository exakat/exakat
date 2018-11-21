<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
    
    public function __construct($destination, $project_root) {
        parent::__construct($destination, $project_root);
        
        $res = shell_exec('svn --version 2>&1');
        if (strpos($res, 'svn') === false) {
            throw new HelperException('SVN');
        }
    }

    public function clone($source) {
        $source = escapeshellarg($source);
        shell_exec("cd {$this->destinationFull}; svn checkout --quiet $source code");
    }

    public function update() {
        $res = shell_exec("cd $this->destinationFull/code; svn update");
        if (preg_match('/Updated to revision (\d+)\./', $res, $r)) {
            return $r[1];
        }
        
        if (preg_match('/At revision (\d+)/', $res, $r)) {
            return $r[1];
        }
        
        return 'Error : '.$res;
    }

    private function getInfo() {
        $res = trim(shell_exec("cd {$this->destinationFull}/code; svn info"));
        
        if (empty($res)) {
            $this->info['svn'] = '';

            return;
        }
        foreach(explode("\n", $res) as $info) {
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

        $res = trim(shell_exec('svn --version 2>&1'));
        if (preg_match('/svn, version ([0-9\.]+) /', $res, $r)) {//
            $stats['installed'] = 'Yes';
            $stats['version'] = $r[1];
        } else {
            $stats['installed'] = 'No';
            $stats['optional'] = 'Yes';
        }
        
        return $stats;
    }

    public function getStatus() {
        $status = array('vcs'       => 'svn',
                        'revision'  => $this->getRevision(),
                        'updatable' => false
                       );

        return $status;
    }
}

?>