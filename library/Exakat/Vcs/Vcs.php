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

abstract class Vcs {
    protected $destination     = '';
    protected $destinationFull = '';
    
    protected $branch = '';
    protected $tag    = '';
    
    protected $checked = false;
    
    public function __construct($destination, $project_root){
        $this->destination     = $destination;
        $this->destinationFull = "$project_root/projects/$destination";
    }

    abstract public function clone($source);

    protected function check() {
        if ($this->checked === true) {
            return true;
        }
        
        $this->selfCheck();
        $this->checked = true;
        
        return true;
    }
    protected function selfCheck() {}

    public function getLineChanges() { return array(); }

    public function update() {}
    
    static public function getVcs($config) {
        if ($config->svn === true) {
            return Svn::class;
        } elseif ($config->hg === true) {
            return Mercurial::class;
        } elseif ($config->bzr === true) {
            return Bazaar::class;
        } elseif ($config->composer === true) {
            return Composer::class;
        } elseif ($config->symlink === true) {
            return Symlink::class;
        } elseif ($config->tbz === true) {
            return Tarbz::class;
        } elseif ($config->tgz === true) {
            return Targz::class;
        } elseif ($config->zip === true) {
            return Zip::class;
        } elseif ($config->copy === true) {
            return Copy::class;
        } elseif ($config->rar === true) {
            return Rar::class;
        } elseif ($config->sevenz === true) {
            return SevenZ::class;
        } elseif ($config->git === true) {
            return Git::class;
        } else {
            return EmptyCode::class;
        }
    }

    public function getStatus() {
        $status = array('updatable' => false,
                       );

        return $status;
    }

    public function setBranch($branch = '') {
        if (!empty($branch)) {
            $this->branch = $branch;
        }
    }

    public function setTag($tag = '') {
        if (!empty($tag)) {
            $this->tag = $tag;
        }
    }
}

?>