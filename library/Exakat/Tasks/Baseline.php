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

namespace Exakat\Tasks;

use Exakat\Exceptions\NoSuchProject;
use Exakat\Tasks\Helpers\BaselineStash;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\InvalidProjectName;

class Baseline extends Tasks {
    const CONCURENCE = self::ANYTIME;

    private const FORMAT = "+ %-4s %10s %12s\n";

    public const ACTIONS = array('list', 'remove', 'save');

    //install, list, local, uninstall, upgrade
    public function run(): void {
        if (!$this->config->project->validate()) {
            throw new InvalidProjectName($this->config->project->getError());
        }

        if ($this->config->project->isDefault()) {
            throw new ProjectNeeded();
        }

        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject((string) $this->config->project);
        }

        if (in_array($this->config->subcommand, self::ACTIONS)) {
            $this->{$this->config->subcommand}();
        } else {
            $this->list();
        }
    }

    private function list() {
        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($this->config->project);
        }

        $list = glob($this->config->project_dir . '/baseline/*.sqlite');
        sort($list);

        print PHP_EOL;
        printf(self::FORMAT, '#', 'Name', 'Date');
        print str_repeat('-', 40) . PHP_EOL;
        foreach($list as $l) {
            if (preg_match('/^dump-(\d+)-(.*?)$/', basename($l, '.sqlite'), $r) ) {
                list(, $id, $name) = $r;
            } else {
                $id = ' ';
                $name = basename($l, '.sqlite');
            }
            $date = date('Y-m-d', filemtime($l));
            printf(self::FORMAT, $id, $name, $date);
        }

        print PHP_EOL . 'Total : ' . count($list) . ' baseline' . (count($list) > 1 ? 's' : '') . PHP_EOL;
    }

    private function remove() {
        $baselineStash = new BaselineStash($this->config);
        $baselineStash->removeBaseline($this->config->baseline_id);
    }

    private function save() {
        $baselineStash = new BaselineStash($this->config);
        $baselineStash->copyPrevious($this->config->dump, $this->config->baseline_set);
        display('Save current audit to ' . $this->config->baseline_set);
    }
}

?>
