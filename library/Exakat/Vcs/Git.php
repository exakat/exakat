<?php
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
use Exakat\Exceptions\VcsError;

class Git extends Vcs {
    private $installed  = false;
    private $optional   = true;
    private $version    = 'unknown';
    private $executable = 'git';
    
    public function __construct($destination, $project_root) {
        parent::__construct($destination, $project_root);
    }
    
    protected function selfCheck() {
        $res = shell_exec("$this->executable --version 2>&1");
        if (strpos($res, 'git') === false) {
            throw new HelperException('git');
        }

        if (preg_match('/git version ([0-9\.]+)/', trim($res), $r)) {//
            $this->installed = true;
            $this->version   = $r[1];
        } else {
            $this->installed = false;
            $this->optional  = true;
        }
    }

    public function clone($source) {
        $this->check();
        $repositoryDetails = parse_url($source);

        if (isset($repositoryDetails['user'])) {
            $repositoryDetails['user'] = urlencode($repositoryDetails['user']);
        } else {
            $repositoryDetails['user'] = '';
        }
        if (isset($repositoryDetails['pass'])) {
            $repositoryDetails['pass'] = urlencode($repositoryDetails['pass']);
        } else {
            $repositoryDetails['pass'] = '';
        }
                
        unset($repositoryDetails['query']);
        unset($repositoryDetails['fragment']);
        $repositoryNormalizedURL = unparse_url($repositoryDetails);

        $codePath = dirname($this->destinationFull);
        $shell = "cd $codePath;GIT_TERMINAL_PROMPT=0 {$this->executable} clone -q $repositoryNormalizedURL";

        if (!empty($this->branch)) {
            display("Check out with branch '$this->branch'");
            $shell .= " -b $this->branch ";
        } elseif (!empty($this->tag)) {
            display("Check out with tag '$this->tag'");
            $shell .= " -b $this->tag ";
        } else {
            display('Check out simple');
        }
        
        $shell .= ' code 2>&1 ';
        $shellResult = shell_exec($shell);

        if (($offset = strpos($shellResult, 'fatal: ')) !== false) {
            $errorMessage = str_replace($repositoryNormalizedURL, $source, $shellResult);
            $errorMessage = trim(substr($shellResult, $offset + 7));

            throw new VcsError('Git', $errorMessage);
        }
    }

    public function update() {
        $this->check();

        $res = shell_exec("cd {$this->destinationFull}/; {$this->executable} branch | grep \\* 2>&1");
        $branch = substr(trim($res), 2);
        
        if (strpos($branch, ' detached at ') === false) {
            $resInitial = shell_exec("cd {$this->destinationFull}/; {$this->executable} show-ref --heads $branch");
        } else {
            $resInitial = shell_exec("cd {$this->destinationFull}/; {$this->executable} checkout --quiet; {$this->executable} pull; {$this->executable} branch | grep '* '");
            $branch = '';
        }
    
        $date = trim(shell_exec("cd {$this->destinationFull}/;GIT_TERMINAL_PROMPT=0  {$this->executable} pull --quiet; {$this->executable} log -1 --format=%cd "));
        $resFinal = shell_exec("cd {$this->destinationFull}/; {$this->executable} show-ref --heads $branch");
        if (strpos($resFinal, ' ') !== false) {
            list($resFinal, ) = explode(' ', $resFinal);
        }
    
        return $resFinal;
    }

    public function setBranch($branch = '') {
        $this->branch = $branch;
    }

    public function setTag($tag = '') {
        $this->tag = $tag;
    }

    public function getBranch() {
        if (!file_exists("{$this->destinationFull}/")) {
            return '';
        }
        $res = shell_exec("cd {$this->destinationFull}; {$this->executable} branch | grep \* 2>&1");
        return trim($res, " *\n");
    }

    public function getRevision() {
        if (!file_exists($this->destinationFull)) {
            return '';
        }
        $res = shell_exec("cd {$this->destinationFull}; {$this->executable} rev-parse HEAD 2>&1");
        return trim($res);
    }
    
    public function getInstallationInfo() {
        $stats = array('installed' => $this->installed === true ? 'Yes' : 'No',
                      );
                      
        if ($this->installed === true) {
            $stats['version'] = $this->version;
            if (version_compare($this->version, '2.3') < 0) {
                $stats['version 2.3'] = 'It is recommended to use git version 2.3 or more recent (' . $this->version . ' detected), for security reasons and the support of GIT_TERMINAL_PROMPT';
            }
        } else {
            $stats['optional'] = 'Yes';
        }

        return $stats;
    }

    public function getStatus() {
        $status = array('vcs'       => 'git',
                        'branch'    => $this->getBranch(),
                        'revision'  => $this->getRevision(),
                        'updatable' => true,
                       );

        return $status;
    }

    public function getDiffLines($r1, $r2) {
        $res = shell_exec("cd {$this->destinationFull}; {$this->executable} diff -U0 -r $r1 -r $r2");

        $file    = '';
        $changes = array();

        $lines = explode(PHP_EOL, $res);
        foreach ($lines as $line) {
            if (preg_match('#diff --git a(/.*?) b(/.*)#', $line, $r)) {
                $file = $r[1];
                continue;
            }
    
            if (preg_match('#@@ \-(\d+)(,(\d+))? \+(\d+)(,(\d+))?( )@@#', $line, $r, PREG_UNMATCHED_AS_NULL)) {
                $c = ($r[6] ?? 1) - ($r[3] ?? 1);
                if ($c !== 0) {
                    $changes[] = array('file' => $file,
                                       'line' => $r[1],
                                       'diff' => $c,
                                       );
                }
            }
        }

        return $changes;
    }
    
    public function getFileModificationLoad() {
        $res = shell_exec("cd {$this->destinationFull}; {$this->executable} log --name-only --pretty=format:");

        $files = array();
        $rows = explode(PHP_EOL, $res);
        foreach ($rows as $row) {
            if (empty($row)) {
                continue;
            }
            if (isset($files[$row])) {
                ++$files[$row];
            } else {
                $files[$row] = 1 ;
            }
        }
        
        return $files;
    }
}

?>