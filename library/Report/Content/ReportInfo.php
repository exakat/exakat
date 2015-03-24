<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Report\Content;

class ReportInfo extends \Report\Content {
    protected $hash = array();

    public function collect() {
        $config = \Config::factory();
        
        $this->array[] = array('Code name', $config->project_name);
        if (!empty($config->project_description)) {
            $this->array[] = array('Code description', $config->project_description);
        }
        if (!empty($config->project_packagist)) {
            $this->array[] = array('Packagist', '<a href="https://packagist.org/packages/'.$config->project_packagist.'">'.$config->project_packagist.'</a>');
        }
        if (!empty($config->project_url)) {
            $this->array[] = array('Home page', '<a href="'.$config->project_url.'">'.$config->project_url.'</a>');
        }
        if (file_exists($config->projects_root.'/projects/'.$this->project.'/code/.git/config')) {
            $gitConfig = file_get_contents($config->projects_root.'/projects/'.$this->project.'/code/.git/config');
            preg_match('#url = (\S+)\s#is', $gitConfig, $r);
            $this->array[] = array('Git URL', $r[1]);
            
            $res = shell_exec('cd '.$config->projects_root.'/projects/'.$this->project.'/code/; git branch');
            $this->array[] = array('Git branch', trim($res));

            $res = shell_exec('cd '.$config->projects_root.'/projects/'.$this->project.'/code/; git rev-parse HEAD');
            $this->array[] = array('Git commit', trim($res));
        } else {
            $this->array[] = array('Repository URL', 'Downloaded archive');
        }

        $datastore = new \Datastore(\Config::factory());
        
        $this->array[] = array('Number of PHP files', $datastore->getHash('files'));
        $this->array[] = array('Number of lines of code', $datastore->getHash('phploc'));

        $this->array[] = array('Report production date', date('r', strtotime('now')));
        
        $this->array[] = array('PHP used', PHP_VERSION);
        $this->array[] = array('Exakat version', \Exakat::VERSION. ' ( Build '. \Exakat::BUILD . ') ');
    }
}

?>
