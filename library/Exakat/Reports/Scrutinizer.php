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

use XmlWriter;
use Exakat\Exakat;

class Scrutinizer extends Reports {
    private $cachedData = '';

    const FILE_EXTENSION = 'xml';
    const FILE_FILENAME  = 'scrutinizer';

    public function generateFileReport($report) {
        $out = new XMLWriter();
        $out->openMemory();
        $out->setIndent(true);

        $out->startElement('file');
        $out->writeAttribute('name', $report['filename']);

        foreach ($report['messages'] as $line => $lineErrors) {
            foreach ($lineErrors as $column => $colErrors) {
                foreach ($colErrors as $error) {

                    $out->startElement('error');
                    $out->writeAttribute('line', $line);
                    $out->writeAttribute('message', $error['message']);
                    $out->writeAttribute('source', $error['source']);
                    $out->endElement();
                    $this->count();
                }
            }
        }

        $out->endElement();
        $this->cachedData .= $out->flush();
    }

    public function generate(string $folder, string $name = self::FILE_FILENAME): string {
        $list = $this->rulesets->getRulesetsAnalyzers($this->themesToShow);

        $resultsAnalyzers = $this->dump->fetchAnalysers($list);
        $resultsAnalyzers->load();

        $results = array();
        $titleCache = array();
        foreach($resultsAnalyzers->toArray() as $row) {
            if (!isset($titleCache[$row['analyzer']])) {
                $titleCache[$row['analyzer']] = $this->docs->getDocs($row['analyzer'], 'name');
            }

            if (!isset($results[$row['file']])) {
                $file = array('filename' => $row['file'],
                              'messages' => array());
                $results[$row['file']] = $file;
            }

            $message = array('source'   => $row['analyzer'],
                             'message'  => $titleCache[$row['analyzer']]);

            if (!isset($results[ $row['file'] ]['messages'][ $row['line'] ])) {
                $results[ $row['file'] ]['messages'][ $row['line'] ] = array(0 => array());
            }
            $results[ $row['file'] ]['messages'][ $row['line'] ][0][] = $message;
        }

        foreach($results as $file) {
            $this->generateFileReport($file);
        }

        $version = Exakat::VERSION;
        $this->cachedData = str_replace("\n", "\n  ", $this->cachedData);
        $return = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<exakat version="$version">
  $this->cachedData
</exakat>
XML;

        if ($name === self::STDOUT) {
            echo $return;
        } else {
            file_put_contents($folder . '/' . $name . '.' . self::FILE_EXTENSION, $return);
        }
    }
}

?>