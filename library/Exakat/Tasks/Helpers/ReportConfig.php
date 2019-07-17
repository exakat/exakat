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

namespace Exakat\Tasks\Helpers;

use Exakat\Config;
use Exakat\Exceptions\NoSuchReport;

class ReportConfig {
    private $name        = 'None';
    private $format      = 'None';
    private $config      = null;
    private $rulesets    = array();
    private $destination = null;

    public function __construct($config, Config $exakat_config) {
        if (is_array($config)) {
            $this->name = key($config);
            $config = array_pop($config);
            $this->name .= " ($config[format])";
            $this->format      = $config['format'];
            // Check for array of string
            $this->rulesets    = $config['rulesets'] ?? array();
            $this->destination = $config['file']     ?? constant("\Exakat\Reports\\$config[format]::FILE_FILENAME");
        } elseif (is_string($config)) {
            $this->reportClass = "\Exakat\Reports\\$config";
            $this->format      = $config;
            $this->name        = $config;
        } else {
            throw new NoSuchReport($config);
        }

        if (!class_exists($this->getFormatClass())) {
            throw new NoSuchReport($this->format);
        }

        $this->config = $exakat_config;
    }

    public function getName() {
        return $this->name;
    }
    
    public function getFormatClass() {
        return "\\Exakat\Reports\\$this->format";
    }

    public function getFormat() {
        return $this->format;
    }

    public function getConfig() {
        return $this->config->duplicate(array('file'   => $this->destination,
                                              'format' => array($this->format)));

    }

    public function getRulesets() {
        $class = $this->getFormatClass();
        $report = new $class($this->config);
    
        $rulesets = $report->dependsOnAnalysis();
        if (empty($rulesets)) {
            if (isset($format['ruleset'])) {
                $rulesets = $format['ruleset'];
            } else {
                $rulesets = array();
            }
        }

        return $rulesets;
    }
}
?>
