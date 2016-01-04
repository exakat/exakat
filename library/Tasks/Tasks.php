<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Tasks;

abstract class Tasks {
    protected $log        = null;
    protected $enabledLog = true;
    
    public function __construct() {
        // Config is the general one.
        $config = \Config::factory();
        
        if ($this->enabledLog) {
            $task = strtolower((new \ReflectionClass($this))->getShortName());
            $this->log = new \Log($task,
                                  $config->projects_root.'/projects/'.$config->project);
        }
        
        if ($config->project != 'default' &&
            file_exists($config->projects_root.'/projects/'.$config->project)) {
            $this->datastore = new \Datastore($config);
        }
    }
    
    protected function checkTokenLimit() {
        $nb_tokens = $this->datastore->getHash('tokens');

        $config = \Config::factory();
        if ($nb_tokens > $config->token_limit) {
            die("Project too large ($nb_tokens / {$config->token_limit})\n");
        }
    }
    
    public abstract function run(\Config $config);
}

?>
