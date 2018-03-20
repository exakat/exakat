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
    public function __construct($destination, $project_root) {
        parent::__construct($destination, $project_root);

        $res = shell_exec('git --version');
        if (strpos($res, 'git') === false) {
            throw new HelperException('git');
        }
    }

    public function clone($source) {
        $repositoryDetails = parse_url($source);

        if (isset($repositoryDetails['user'])) {
            $repositoryDetails['user'] = escapeshellarg($repositoryDetails['user']);
        } else {
            $repositoryDetails['user'] = 'exakat';
        }
        if (isset($repositoryDetails['pass'])) {
            $repositoryDetails['pass'] = escapeshellarg($repositoryDetails['pass']);
        } else {
            $repositoryDetails['pass'] = 'exakat';
        }
                
        unset($repositoryDetails['query']);
        unset($repositoryDetails['fragment']);
        $repositoryNormalizedURL = unparse_url($repositoryDetails);

        $shell = "cd {$this->destinationFull}; git clone -q $repositoryNormalizedURL";
        /*
        if (!empty($this->config->branch) &&
            $this->config->branch !== 'master') {
            display("Check out with branch ".$this->config->branch);
            $shell .= ' -b '.$this->config->branch.' ';

        } elseif (!empty($this->config->tag)) {
            display("Check out with tag ".$this->config->tag);
            $shell .= ' -b '.$this->config->tag.' ';

        }
        */
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
        
        if (strpos($branch, ' detached at ') !== false) {
            $resInitial = shell_exec("cd {$this->destinationFull}/code/; git checkout master --quiet; git pull");
            $branch = 'master';
        } else {
            $resInitial = shell_exec("cd {$this->destinationFull}/code/; git show-ref --heads $branch");
        }
    
        $date = trim(shell_exec("cd {$this->destinationFull}/code/; git pull --quiet; git log -1 --format=%cd "));
        $resFinal = shell_exec("cd {$this->destinationFull}/code/; git show-ref --heads $branch");
        if (strpos($resFinal, ' ') !== false) {
            list($resFinal, ) = explode(' ', $resFinal);
        }
    
        return $resFinal;
    }

    public function getUrl() {
        if (!file_exists($this->destinationFull.'/code/.git/config')) {
            return 'No URL';
        }
        $gitConfig = file_get_contents($this->destinationFull.'/code/.git/config');
        if (preg_match('#url = (\S+)\s#is', $gitConfig, $r)) {
            $url = $r[1];
        } else {
            $url = 'No URL';
        }

        return $url;
    }
    
    public function getBranch() {
        $res = shell_exec('cd '.$this->destinationFull.'/code/; git branch');
        return trim($res, " *\n");
    }

    public function getRevision() {
        $res = shell_exec('cd '.$this->destinationFull.'/code/; git rev-parse HEAD');
        return trim($res);
    }
}

?>