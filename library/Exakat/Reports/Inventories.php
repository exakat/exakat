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


namespace Exakat\Reports;


class Inventories extends Reports {
    const FILE_EXTENSION = '';
    const FILE_FILENAME  = 'inventories';

    private const NO_DATA_TO_REPORT = 'This file is left intentionally empty. There was nothing to report.';

    public function generate(string $folder, string $name = self::FILE_FILENAME): string {
        if ($name == self::STDOUT) {
            print "Can't produce Inventories format to stdout\n";
            return '';
        }

        $path = "$folder/$name";

        if (file_exists($path)) {
            rmdirRecursive($path);
        }
        mkdir($path, 0777);

        $this->saveInventory('Constants/Constantnames',      "$folder/$name/constants.csv");
        $this->saveInventory('Functions/Functionnames',      "$folder/$name/functions.csv");
        $this->saveInventory('Classes/Classnames',           "$folder/$name/classes.csv");
        $this->saveInventory('Interfaces/Interfacenames',    "$folder/$name/interfaces.csv");
        $this->saveInventory('Traits/Traitnames',            "$folder/$name/traits.csv");
        $this->saveInventory('Namespaces/Namespacesnames',   "$folder/$name/namespaces.csv");
        $this->saveInventory('Exceptions/DefinedExceptions', "$folder/$name/exceptions.csv");

        $this->saveTable(    'variables',                     "$folder/$name/variables.csv", array('variable', 'type'));
        $this->saveInventory('Php/IncomingVariables',         "$folder/$name/incomingGPC.csv");
        $this->saveInventory('Php/SessionVariables',          "$folder/$name/sessions.csv");
        $this->saveInventory('Variables/GlobalVariables',     "$folder/$name/globals.csv");

        $this->saveInventory('Php/DateFormats',               "$folder/$name/dateformats.csv");
        $this->saveInventory('Type/Url',                      "$folder/$name/sql.csv");
        $this->saveInventory('Type/Regex',                    "$folder/$name/regex.csv");
        $this->saveInventory('Type/Sql',                      "$folder/$name/sql.csv");
        $this->saveInventory('Type/Email',                    "$folder/$name/email.csv");
        $this->saveInventory('Type/UnicodeBlock',             "$folder/$name/unicode-block.csv");
        $this->saveInventory('Type/GPCIndex',                 "$folder/$name/email.csv");
        $this->saveInventory('Type/Md5string',                "$folder/$name/md5string.csv");
        $this->saveInventory('Type/Mime',                     "$folder/$name/mime.csv");
        $this->saveInventory('Type/Pack',                     "$folder/$name/pack.csv");
        $this->saveInventory('Type/Printf',                   "$folder/$name/printf.csv");
        $this->saveInventory('Type/Path',                     "$folder/$name/path.csv");
        $this->saveInventory('Type/Shellcommands',            "$folder/$name/shellcmd.csv");

        $this->saveHashResults('ParameterNames',              "$folder/$name/parameterNames.csv", array('Parameter', 'Occurrences'));

        $this->saveAtom('Integer',      "$path/integers.csv");
        $this->saveAtom('ArrayLiteral', "$path/arrays.csv");
        $this->saveAtom('Heredoc',      "$path/heredoc.csv");
        $this->saveAtom('Float',        "$path/float.csv");
        $this->saveAtom('String',       "$path/strings.csv");

        $this->saveTable('globalVariables',       "$path/globals.csv", array('variable', 'file', 'line', 'isRead', 'isModified', 'type'));
        $this->saveTable('inclusions',       "$path/inclusions.csv", array('including', 'included'));

        return '';
    }

    private function saveInventory(string $analyzer, string $file): void {
        $res = $this->dump->fetchAnalysers(array($analyzer));
        $fp = fopen($file, 'w+');
        fputcsv($fp, array('Name', 'File', 'Line'));
        foreach($res->toArray() as $row) {
            fputcsv($fp, $row);
        }
        $this->count($res->getCount());
        fclose($fp);
    }

    private function saveAtom(string $atom, string $file): void {
        $res = $this->dump->fetchTable('literal' . $atom);
        if ($res->isEmpty() === true) {
            file_put_contents($file, self::NO_DATA_TO_REPORT);
            return;
        }
        $fp = fopen($file, 'w+');
        fputcsv($fp, array('Name', 'File', 'Line'));
        foreach($res->toArray() as $row) {
            fputcsv($fp, $row);
        }
        $this->count($res->getCount());
        fclose($fp);
    }

    private function saveTable(string $table, string $file, array $columns): void {
        $res = $this->dump->fetchTable($table);
        if ($res->isEmpty() === true) {
            file_put_contents($file, self::NO_DATA_TO_REPORT);
            return ;
        }

        $fp = fopen($file, 'w+');
        fputcsv($fp, $columns);

        foreach($res->toArray() as $row) {
            $r = array();
            foreach($columns as $c) {
                $r[$c] = $row[$c];
            }
            fputcsv($fp, $r);
        }
        $this->count($res->getCount());
        fclose($fp);
    }

    private function saveHashResults(string $name, string $file, array $columns = array()): void {
        $res = $this->dump->fetchHashResults($name);
        if ($res->isEmpty() === true) {
            file_put_contents($file, self::NO_DATA_TO_REPORT);
            return ;
        }

        $fp = fopen($file, 'w+');
        fputcsv($fp, $columns);

        foreach($res->toArray() as $row) {
            fputcsv($fp, $row);
        }
        $this->count($res->getCount());
        fclose($fp);
    }

    public function dependsOnAnalysis(): array {
        return array('Inventories',
                     );
    }

}

?>