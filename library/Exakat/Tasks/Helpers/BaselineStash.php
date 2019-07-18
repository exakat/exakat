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

class BaselineStash {
    private $baseline_strategy = 'none';
    private $project_dir       = '';

    function __construct(Config $config) {
        $this->baseline_strategy = $config->baseline_set;
        $this->project_dir       = $config->project_dir;
    }

    public function copyPrevious($previous) {
        if ($this->baseline_strategy === 'none') {
            // Nothing to do
            return;
        }

        if (file_exists($previous)) {
            $baseline_dir = dirname($previous).'/baseline';
            if (!file_exists($baseline_dir)) {
                mkdir($baseline_dir,0700);
                
                // Can't create the dir, no baseline dir
                if (!file_exists($baseline_dir)) {
                    return;
                }
            }

            $baselines = glob("$baseline_dir/dump-*.sqlite");
            if (empty($baselines)) {
                $baselines = array();
                $last_id = 1;
            } else {
                usort($baselines, function($a, $b) { return $a <=> $b;} ); // simplistic sorting
                $last = $baselines[count($baselines) - 1];
                $last_id = preg_match('/dump-(\d+)-/', $last, $r) ? (int) $r[1] : 1;
            }

            if ($this->baseline_strategy === 'one') {
                // Reuse the last that exists
                $sqliteFilePrevious = array_pop($baselines);
                copy($previous, $sqliteFilePrevious);
                return;
            }

            if ($this->baseline_strategy === 'always') {
                // Create a new 
                // md5 is here for uuid purpose.
                $sqliteFilePrevious = $baseline_dir.'/dump-'.($last_id + 1).'-'.substr(md5($baseline_dir.($last_id + 1)), 0, 7).'.sqlite';
                copy($previous, $sqliteFilePrevious);
                return;
            }

            // Use baseline_strategy as a name, only if it doesn't exist yet
            $previousDump = preg_grep('/-'.preg_quote($this->baseline_strategy).'\.sqlite$/', $baselines);
            if (empty($previousDump)) {
                // Create a new : use the strategy as the last one
                $sqliteFileBaseline = $baseline_dir.'/dump-'.($last_id + 1).'-'.$this->baseline_strategy.'.sqlite';
                copy($previous, $sqliteFileBaseline);
                return;
            }
        }
    }

    public function removeBaseline($id) {
        if ((int) $id !== 0) {
            $id = (int) $id;
            $baselines = glob("{$this->project_dir}/baseline/dump-$id-*.sqlite");
            if (empty($baselines)) {
                display("$id : no such baseline  to remove");
            } else {
                $file = array_pop($baselines);
                unlink($file);
                display(substr(basename($file, '.sqlite'), 5).' was removed');
            }
            return;
        }

        $baselines = glob("{$this->project_dir}/baseline/dump-*-$id.sqlite");
        if (empty($baselines)) {
            display("$id : no such baseline to remove");
        } else {
            $file = array_pop($baselines);
            unlink($file);
            display(substr(basename($file, '.sqlite'), 5).' was removed');
        }
    }
}

?>
