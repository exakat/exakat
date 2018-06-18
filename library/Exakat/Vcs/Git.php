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
use Exakat\Exceptions\VcsError;

class Git extends Vcs {
    private $installed = false;
    private $optional  = true;
    private $version   = 'unknown';
    private $tag       = '';
    private $branch    = 'master';
    
    
    public function __construct($destination, $project_root) {
        parent::__construct($destination, $project_root);

        $res = shell_exec('git --version 2>&1');
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

        $shell = "cd {$this->destinationFull};GIT_TERMINAL_PROMPT=0 git clone -q $repositoryNormalizedURL";

        if (empty($this->tag)) {
            display("Check out with branch $this->tag");
            $shell .= " -b $this->branch ";
        } else {
            display("Check out with tag $this->tag");
            $shell .= " -b $this->tag ";
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
        $res = shell_exec("cd {$this->destinationFull}/code/; git branch | grep \\*");
        $branch = substr(trim($res), 2);
        
        if (strpos($branch, ' detached at ') === false) {
            $resInitial = shell_exec("cd {$this->destinationFull}/code/; git show-ref --heads $branch");
        } else {
            $resInitial = shell_exec("cd {$this->destinationFull}/code/; git checkout master --quiet; git pull");
            $branch = 'master';
        }
    
        $date = trim(shell_exec("cd {$this->destinationFull}/code/;GIT_TERMINAL_PROMPT=0  git pull --quiet; git log -1 --format=%cd "));
        $resFinal = shell_exec("cd {$this->destinationFull}/code/; git show-ref --heads $branch");
        if (strpos($resFinal, ' ') !== false) {
            list($resFinal, ) = explode(' ', $resFinal);
        }
    
        return $resFinal;
    }

    public function setBranch($branch) {
        $this->branch = $branch;
    }

    public function setTag($tag) {
        $this->tag = $tag;
    }

    public function getBranch() {
        $res = shell_exec("cd {$this->destinationFull}/code/; git branch 2>&1");
        return trim($res, " *\n");
    }

    public function getRevision() {
        $res = shell_exec("cd {$this->destinationFull}/code/; git rev-parse HEAD 2>&1");
        return trim($res);
    }
    
    public function getInstallationInfo() {
        $stats = array('installed' => $this->installed === true ? 'Yes' : 'No',
                      );
                      
        if ($this->installed === true) {
            $stats['version'] = $this->version;
            if (version_compare($this->version, '2.3') < 0) {
                $stats['version 2.3'] = 'It is recommended to use git version 2.3 or more recent ('.$this->version.' detected), for security reasons and the support of GIT_TERMINAL_PROMPT';
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
}

?>