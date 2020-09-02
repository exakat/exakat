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

namespace Exakat\Configsource;

use Exakat\Project;

class DatastoreConfig extends Config {
    private $datastore             = null;
    protected $ignore_dirs         = array();
    protected $include_dirs        = array();
    protected $ignore_rules        = array();
    protected $project_name        = '';
    protected $project_url         = '';
    protected $project_vcs         = '';
    protected $project_description = '';
    protected $project_branch      = '';
    protected $project_tag         = '';
    protected $project             = '';
    protected $file_extensions     = array();
    protected $stubs               = array();

    protected $options = array('phpversion'          => '',
                               'project_name'        => '',
                               'project_url'         => '',
                               'project_vcs'         => '',
                               'project_description' => '',
                               'project_branch'      => '',
                               'project_tag'         => '',
                               'file_extensions'     => array(),
                               );

    public function __construct() {
        $this->datastore = exakat('datastore');
    }

    public function setProject(Project $project) : void {
        $this->project = $project;
    }

    public function loadConfig(Project $project) : ?string {
        $this->options['phpversion'] = $this->datastore->getHash('php_version');
        $this->ignore_dirs           = json_decode($this->datastore->getHash('ignore_dirs')     ?? '[]');
        $this->include_dirs          = json_decode($this->datastore->getHash('include_dirs')    ?? '[]');
        $this->file_extensions       = json_decode($this->datastore->getHash('file_extensions') ?? '[]');
        $this->stubs                 = json_decode($this->datastore->getHash('stubs_config')    ?? '[]');

        $this->project_name        = $this->datastore->getHash('project');
        $this->project_url         = $this->datastore->getHash('vcs_url');
        $this->project_vcs         = $this->datastore->getHash('vcs_type');
        $this->project_description = $this->datastore->getHash('project_description');
        $this->project_branch      = $this->datastore->getHash('vcs_branch');
        $this->project_tag         = $this->datastore->getHash('vcs_tag');

        return 'datastore';
    }
}

?>