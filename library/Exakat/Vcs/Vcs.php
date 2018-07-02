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

abstract class Vcs {
    protected $destination     = '';
    protected $destinationFull = '';
    
    protected $branch = 'master';
    protected $tag    = '';
    
    public function __construct($destination, $project_root){
        $this->destination     = $destination;
        $this->destinationFull = "$project_root/projects/$destination";
    }

    abstract public function clone($source);

    abstract public function update();
    
    static public function getVcs($config) {
        if ($config->git === true) {
            return '\Exakat\Vcs\Git';
        } elseif ($config->svn === true) {
            return '\Exakat\Vcs\Svn';
        } elseif ($config->hg === true) {
            return '\Exakat\Vcs\Mercurial';
        } elseif ($config->bzr === true) {
            return '\Exakat\Vcs\Bazaar';
        } elseif ($config->composer === true) {
            return '\Exakat\Vcs\Composer';
        } elseif ($config->symlink === true) {
            return '\Exakat\Vcs\Symlink';
        } elseif ($config->tbz === true) {
            return '\Exakat\Vcs\Tarbz';
        } elseif ($config->tgz === true) {
            return '\Exakat\Vcs\Targz';
        } elseif ($config->zip === true) {
            return '\Exakat\Vcs\Zip';
        } elseif ($config->copy === true) {
            return '\Exakat\Vcs\Copy';
        } else {
            return '\Exakat\Vcs\EmptyCode';
        }
    }

    public function getStatus() {
        $status = array('updatable' => false,
                       );

        return $status;
    }

    protected function setBranch($branch = '') {
        if (!empty($branch)) {
            $this->branch = $branch;
        }
    }

    protected function setTag($tag = '') {
        if (!empty($tag)) {
            $this->tag = $tag;
        }
    }
}

?>