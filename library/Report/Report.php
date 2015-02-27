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


namespace Report;

class Report {
    static public $formats = array('Devoops', 'Json', 'Sqlite');
    
    protected $client    = null;
    protected $project   = null;

    private $content   = null;
    private $current   = null;
    private $currentLevel1 = null;
    private $currentLevel2 = null;

    public function __construct($project, $client) {
        $this->project = $project;
        $this->client  = $client;
        
        $this->content = new \Report\Template\Section('');
        $this->current = $this->content;
        $this->root    = $this->content;
    }

    protected function getContent($name) {
        $nsname = "Report\\Content\\$name";
        $content = new $nsname();

        $content->setProject($this->project);
        $content->setNeo4j($this->client);
        
        return $content;
    }

    protected function addContent($type, $data = null, $css = null) {
        if (is_string($data) && strpos($data, ' ') === false) { // rough check if this may be a class name
            $content = $this->getContent($data);
            $content->collect();
        } else {
            $content = $data;
        }
        
        return $this->current->addContent($type, $content, $css);
    }

    protected function createLevel1($name) {
        $section = $this->root->addContent('Section', $name);
        $section->setLevel(1);

        $this->current = $section;
        $this->currentLevel1 = $section;
    }

    protected function createLevel2($name) {
        // @todo check that current is level 1 ? 
        $section = $this->currentLevel1->addContent('Section', $name);
        $section->setLevel(2);

        $this->current = $section;
        $this->currentLevel2 = $section;
    }

    public function render($format, $filename = null) {
        $format = "\\Report\\Format\\$format";

        $this->output = new $format();
        $this->output->setProjectName($this->project);
        $this->output->setProjectUrl('');
        $this->output->setSummaryData($this->root);
        
        foreach($this->root->getContent() as $c) {
            $c->render($this->output);
        }
        
        if ($filename !== null) {
            $config = \Config::factory();
            
            return $this->output->toFile($config->projects_root.'/projects/'.$config->project.'/'.$filename.'.'.$this->output->getExtension());
        } else {
            die("No filename? ".__METHOD__);
        }
    }
}
