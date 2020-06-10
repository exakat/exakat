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

class BaselineStash {
    const STRATEGIES = array('none', 'always');

    // 'none', 'always', '<Name>'
    private $baseline_strategy = 'none';
    private $baseline_dir       = '';
    private $use               = 'none';

    const NO_BASELINE          = '';

    public function __construct(Config $config) {
        $this->baseline_strategy = $config->baseline_set;
        $this->baseline_dir      = $config->project_dir . '/baseline';
        $this->use               = $config->baseline_use;

        if (!file_exists($this->baseline_dir)) {
            if (!mkdir($this->baseline_dir,0700)) {
                display('Could not create the baseline directory. No baseline will be saved.');
            }
        }
    }

    public function copyPrevious(string $previous, string $name = ''): void {
        if (!file_exists($previous)) {
            display("No previous audit found. Omitting baseline\n");

            return;
        }

        if (!empty($name) && !in_array($name, self::STRATEGIES)) {
            // overwrite
            if (!copy($previous, $this->baseline_dir . '/' . $name . '.sqlite')) {
                display('Could not save the baseline with the name ' . $name);
            }

            return;
        }

        if ($this->baseline_strategy === 'none') {
            // Nothing to do
            return;
        }

        if ($this->baseline_strategy === 'always') {
            $baselines = glob("{$this->baseline_dir}/dump-*.sqlite");
            if (empty($baselines)) {
                $last_id = 1;
            } else {
                usort($baselines, function (string $a, string $b) { return $b <=> $a;} ); // simplistic reverse sorting
                $last = $baselines[0];
                $last_id = preg_match('/dump-(\d+)-/', $last, $r) ? (int) $r[1] : 1;
            }

            if ($this->baseline_strategy === 'always') {
                // Create a new
                // md5 is here for uuid purpose.
                $sqliteFilePrevious = $this->baseline_dir . '/dump-' . ($last_id + 1) . '-' . substr(md5($this->baseline_dir . ($last_id + 1)), 0, 7) . '.sqlite';
                if (!copy($previous, $sqliteFilePrevious)) {
                    display('Could not save the baseline with the name ' . $name);
                }

                return;
            }
        }
    }

    public function removeBaseline(string $id): void {
        $id = basename($id);
        if (file_exists("{$this->baseline_dir}/$id.sqlite")) {
            display("Removing baseline '$id'\n");
            unlink("{$this->baseline_dir}/$id.sqlite");

            return;
        }

        $baselines = glob("{$this->baseline_dir}/dump-*-$id.sqlite");
        if (!empty($baselines) && count($baselines) === 1) {
            $baseline = basename($baselines[0], '.sqlite');
            display("Removing baseline '$baseline'\n");

            unlink($baselines[0]);

            return;
        }

        display("Could not find $id baseline\n");
    }

    public function getBaseline(): string {
        if ($this->baseline_strategy === 'none') {
            return self::NO_BASELINE;
        }

        if ($this->baseline_strategy === 'always') {
            $baselines = glob("{$this->baseline_dir}//dump-*-*.sqlite");
            if (empty($baselines)) {
                return self::NO_BASELINE;
            }

            // Get the last one
            sort($baselines);
            return array_pop($baselines);
        }

        // full name in use
        if (file_exists("{$this->baseline_dir}/{$this->baseline_strategy}.sqlite")) {
            return "{$this->baseline_dir}/{$this->baseline_strategy}.sqlite";
        }

        // dump-xxx-AAAAAAA.sqlite name
        if (file_exists("{$this->baseline_dir}/dump-\d+-{$this->baseline_strategy}.sqlite")) {
            return "{$this->baseline_dir}/{$this->baseline_strategy}.sqlite";
        }

        return self::NO_BASELINE;
    }
}

?>
