<?php
/*
 * Copyright 2012-2019 Damien Seguy Ð Exakat SAS <contact(at)exakat.io>
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


class History extends Reports {
    const FILE_FILENAME  = 'history';
    const FILE_EXTENSION = 'sqlite';

    public function generate(string $folder, string $name = 'history'): string {
        if ($name === self::STDOUT) {
            print "Can't produce History format to stdout\n";

            return '';
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

        $serial = $this->dump->fetchHash('dump_serial');
        if ($serial->isEmpty())  {
            print "Couldn't get a serial number for the current audit. Ignoring\n";

            return '';
        }

        $already = $sqlite->querysingle('SELECT id FROM hash WHERE key="dump_serial" AND value="' . $serial->toString() . '"');
        if (!empty($already))  {
            print "Dataset #{$serial->toString()} is already in history. Ignoring\n";

            return '';
        }

        display("Add dataset #{$serial->toString()} to history\n");

        $sqlite->query('ATTACH "' . $this->config->dump . '" AS dump');

        $query = "INSERT INTO hash SELECT NULL, \"{$serial->toString()}\", key, value FROM dump.hash";
        $sqlite->query($query);

        $query = "INSERT INTO resultsCounts SELECT NULL, \"{$serial->toString()}\", analyzer, count FROM dump.resultsCounts";
        $sqlite->query($query);

        return '';
    }
}

?>