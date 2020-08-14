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

            if (!isset($config['format'])) {
                throw new NoSuchReport("Undefined format for $this->name\n");
            }

            $this->name       .= " ($config[format])";
            $this->format      = $config['format'];
            if (!class_exists($this->getFormatClass())) {
                throw new NoSuchReport($this->format);
            }

            // Check for array of string
            $this->rulesets    = $config['rulesets'] ?? array();
            $this->rulesets    = makeArray($this->rulesets);
            $this->destination = $config['file']     ?? constant("\Exakat\Reports\\$config[format]::FILE_FILENAME");
        } elseif (is_string($config)) {
            $this->format      = $config;
            if (!class_exists($this->getFormatClass())) {
                throw new NoSuchReport($this->format);
            }

            $this->name        = $config;
            $this->rulesets    = $exakat_config->project_rulesets ?? array();
            $this->destination = $exakat_config->file ?: constant("\Exakat\Reports\\$config::FILE_FILENAME");
        } else {
            throw new NoSuchReport($config);
        }

        $this->config = $exakat_config;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getFormatClass(): string {
        return '\Exakat\Reports\\' . ucfirst(strtolower($this->format));
    }

    public function getFormat(): string {
        return $this->format;
    }

    public function getFile(): string {
        return $this->destination;
    }

    public function getConfig(): Config {
        return $this->config->duplicate(array('file'             => $this->destination,
                                              'format'           => array($this->format),
                                              'project_rulesets' => $this->rulesets,
                                              ));
    }

    public function getRulesets(): array {
        $class = $this->getFormatClass();
        $report = new $class($this->config);

        $rulesets = $report->dependsOnAnalysis();
        if (empty($rulesets)) {
            $rulesets = $this->rulesets;
        }

        return $rulesets;
    }
}
?>
