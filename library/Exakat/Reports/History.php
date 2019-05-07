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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;
use Exakat\Reports\Helpers\Results;

class History extends Reports {
    const FILE_FILENAME  = 'history';
    const FILE_EXTENSION = 'sqlite';

    public function __construct($config) {

    }

    public function generate($folder, $name = 'history') {
        if ($name === self::STDOUT) {
            print "Can't produce History format to stdout\n";
            return false;
        }

        $sqlite = new \Sqlite3("$folder/$name." . self::FILE_EXTENSION);
        $query = "SELECT name FROM sqlite_master WHERE type='table' AND name = 'hash';";
        $res = $sqlite->querySingle($query);
        
        if (empty($res)) {
            $sqlite->query(<<<'SQLITE'
CREATE TABLE hash (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  serial TEXT,
  key TEXT,
  value TEXT
);

SQLITE
);

            $sqlite->query(<<<'SQLITE'
CREATE TABLE resultsCounts ( 
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    serial TEXT,
    analyzer STRING,
    count INTEGER DEFAULT -6,
    CONSTRAINT "analyzers" UNIQUE (analyzer) ON CONFLICT REPLACE
);

SQLITE
);
        }

        $serial = $this->sqlite->querysingle('SELECT value FROM hash WHERE key="dump_serial"');
        if (empty($serial))  {
            print "Couldn't get a an for the current dataset. Ignoring\n";
            return;
        }

        $already = $sqlite->querysingle('SELECT id FROM hash WHERE key="dump_serial" AND value="' . $serial . '"');
        if (!empty($already))  {
            print "Dataset #$serial is already in history. Ignoring\n";
            return;
        }
        
        display("Add dataset #$serial to history\n");

        $sqlite->query('ATTACH "' . $this->config->dump . '" AS dump');

        $query = "INSERT INTO hash SELECT NULL, \"$serial\", key, value FROM dump.hash";
        $sqlite->query($query);

        $query = "INSERT INTO resultsCounts SELECT NULL, \"$serial\", analyzer, count FROM dump.resultsCounts";
        $sqlite->query($query);

    }
}

?>