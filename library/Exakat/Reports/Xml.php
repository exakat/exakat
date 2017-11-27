<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Analyzer\Analyzer;
use Exakat\Exakat;

/**
 * Xml report for PHP_CodeSniffer.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Gabriele Santini <gsantini@sqli.com>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2009-2014 SQLI <www.sqli.com>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Xml report for PHP_CodeSniffer.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Gabriele Santini <gsantini@sqli.com>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2009-2014 SQLI <www.sqli.com>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Xml extends Reports {
    private $cachedData = '';

    const FILE_EXTENSION = 'xml';
    const FILE_FILENAME  = 'exakat';

    /**
     * Generate a partial report for a single processed file.
     *
     * Function should return TRUE if it printed or stored data about the file
     * and FALSE if it ignored the file. Returning TRUE indicates that the file and
     * its data should be counted in the grand totals.
     *
     * @param array                $report      Prepared report data.
     * @param PHP_CodeSniffer_File $phpcsFile   The file being reported on.
     * @param boolean              $showSources Show sources?
     * @param int                  $width       Maximum allowed line width.
     *
     * @return boolean
     */
    public function generateFileReport($report) {
        $out = new XMLWriter();
        $out->openMemory();
        $out->setIndent(true);

        if ($report['errors'] === 0 && $report['warnings'] === 0) {
            // Nothing to print.
            return false;
        }

        $out->startElement('file');
        $out->writeAttribute('name', $report['filename']);
        $out->writeAttribute('errors', $report['errors']);
        $out->writeAttribute('warnings', $report['warnings']);
        $out->writeAttribute('fixable', $report['fixable']);

        foreach ($report['messages'] as $line => $lineErrors) {
            foreach ($lineErrors as $column => $colErrors) {
                foreach ($colErrors as $error) {

                    $error['type'] = strtolower($error['type']);

                    $out->startElement($error['type']);
                    $out->writeAttribute('line', $line);
                    $out->writeAttribute('column', $column);
                    $out->writeAttribute('source', $error['source']);
                    $out->writeAttribute('severity', $error['severity']);
                    $out->writeAttribute('fixable', (int) $error['fixable']);
                    $out->text($error['message']);
                    $out->endElement();
                    $this->count();
                }
            }
        }//end foreach

        $out->endElement();
        $this->cachedData .= $out->flush();
    }//end generateFileReport()


    /**
     * Prints all violations for processed files, in a proprietary XML format.
     *
     * @param string  $cachedData    Any partial report data that was returned from
     *                               generateFileReport during the run.
     * @param int     $totalFiles    Total number of files processed during the run.
     * @param int     $totalErrors   Total number of errors found during the run.
     * @param int     $totalWarnings Total number of warnings found during the run.
     * @param int     $totalFixable  Total number of problems that can be fixed.
     * @param boolean $showSources   Show sources?
     * @param int     $width         Maximum allowed line width.
     * @param boolean $toScreen      Is the report being printed to screen?
     *
     * @return void
     */
    public function generate($folder, $name = null) {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.implode('", "', $list).'"';

        $sqlite = new \Sqlite3($folder.'/dump.sqlite');
        $sqlQuery = 'SELECT * FROM results WHERE analyzer in ('.$list.')';
        $res = $sqlite->query($sqlQuery);

        $results = array();
        $titleCache = array();
        $severityCache = array();
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            if (!isset($results[$row['file']])) {
                $file = array('errors'   => 0,
                              'warnings' => 0,
                              'fixable'  => 0,
                              'filename' => $row['file'],
                              'messages' => array());
                $results[$row['file']] = $file;
            }

            if (!isset($titleCache[$row['analyzer']])) {
                $analyzer = Analyzer::getInstance($row['analyzer'], null, $this->config);
                $titleCache[$row['analyzer']] = $analyzer->getDescription()->getName();
                $severityCache[$row['analyzer']] = $analyzer->getSeverity();
            }

            $message = array('type'     => 'warning',
                             'source'   => $row['analyzer'],
                             'severity' => $severityCache[$row['analyzer']],
                             'fixable'  => 'fixable',
                             'message'  => $titleCache[$row['analyzer']]);

            if (!isset($results[ $row['file'] ]['messages'][ $row['line'] ])) {
                $results[ $row['file'] ]['messages'][ $row['line'] ] = array(0 => array());
            }
            $results[ $row['file'] ]['messages'][ $row['line'] ][0][] = $message;

            ++$results[ $row['file'] ]['warnings'];
        }

        foreach($results as $file) {
            $this->generateFileReport($file);
        }

        $return = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.'<phpcs version="'.Exakat::VERSION.'">'.PHP_EOL.$this->cachedData.'</phpcs>'.PHP_EOL;

        if ($name === null) {
            return $return;
        } else {
            file_put_contents($folder.'/'.$name.'.'.self::FILE_EXTENSION, $return);
            return true;
        }
    }
}

?>