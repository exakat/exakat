<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

use Exakat\Config;
use Exakat\Stats;

class Stat extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run() {
        $stats = new Stats($this->gremlin);
        if ($this->config->filename) {
            $stats->setFileFilter($this->config->filename);
        }
        $stats->collect();
        $stats = $stats->toArray();

        if ($this->config->json) {
            $output = json_encode($stats);
        } elseif ($this->config->table) {
            $output = $this->table_encode($stats);
        } else {
            $output = $this->text_encode($stats);
        }

        if ($this->config->output) {
            $outputFile = fopen($this->config->filename, 'w+');
            fwrite($outputFile, $output);
            fclose($outputFile);
        } else {
            echo $output;
        }
    }

    private function table_encode($stats) {
        $html = '<html><body>';

        foreach($stats as $name => $value) {
            $html .= "<tr><td>$name</td><td>$value</td></tr>\n";
        }

        $html .= '</body></html>';
        return $html;
    }

    private function text_encode($stats) {
        $html = "Statistics for the whole server\n\n";

        foreach($stats as $name => $value) {
            $html .= "$name : $value\n";
        }

        $html .= "\n";
        return $html;
    }
}

?>
