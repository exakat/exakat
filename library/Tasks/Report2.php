<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Tasks;

class Report2 extends Tasks {
    public function run(\Config $config) {
        $reportClass = "\\Report\\Report\\".$config->report;

        if (!class_exists($reportClass)) {
            die("Report '{$config->report}' doesn't exist.\nAborting\n");
        }

        $this->checkTokenLimit();
        
        if (!class_exists("\\Report\\Format\\".$config->format)) {
            die("Format '".$config->format."' doesn't exist. Choose among : ".implode(', ', \Report\Report::$formats)."\nAborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$config->project)) {
            die("Project '{$config->project} doesn't exist yet. Run init to create it.\nAborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$config->project.'/datastore.sqlite')) {
            die("Project hasn't been analyzed. Run project first.\nAborting\n");
        }

        \Analyzer\Analyzer::$datastore = $this->datastore;
        // errors, warnings, fixable and filename
        // line number => columnnumber => type, source, severity, fixable, message

        $sqlQuery = 'SELECT * FROM results';
        if ($config->program !== null) {
            $analyzer = $config->program;
            if (\Analyzer\Analyzer::getClass($analyzer)) {
                $sqlQuery .= " WHERE analyzer='$analyzer'";
                display( "Reporting results in analyze '$analyzer'\n");
            } else {
                $r = \Analyzer\Analyzer::getSuggestionClass($analyzer);
                if (count($r) > 0) {
                    echo 'did you mean : ', implode(', ', str_replace('_', '/', $r)), "\n";
                }
                die("No such class as '$analyzer'. Aborting\n");
            }
        } elseif ($config->thema !== null) {
            $thema = $config->thema;

            if (!$analyzersClass = \Analyzer\Analyzer::getThemeAnalyzers($thema)) {
                die("No such thema as '$thema'. Aborting\n");
            }

            display( "Reporting results in thema '$thema'\n");
            $sqlQuery .= " WHERE analyzer IN ('".join("', '", $analyzersClass)."')";
        } else {
            display( "Reporting ALL results\n");
        }

        $sqlite = new \Sqlite3($config->projects_root.'/projects/'.$config->project.'/dump.sqlite');
        $res = $sqlite->query($sqlQuery);
        
        $results = array();
        $titleCache = array();
        $severityCache = array();
        $i = 0;
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
                $analyzer = \Analyzer\Analyzer::getInstance($row['analyzer']);
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
            
            $i++;
        }

        display( 'Building report '.$config->report.' for project '.$config->project.' in file '.$config->file.', with format '.$config->format."\n");
        $begin = microtime(true);

//        $report = new \Reports\Xml();
//        echo $report->generate( $results);

//        $report = new \Reports\Text();
//        if ($config->file == 'stdout') {
//            echo $report->generate($results);
//        } else {
//            file_put_contents($config->projects_root.'/projects/'.$config->project.'/'.$config->file.'.'.$report->extension, $report->generate( $results));
//            display("Reported ".$report->count." messages\n");
//        }

        $report = new \Reports\Devoops();
        echo $report->generate( $config->projects_root.'/projects/'.$config->project, 'report');

        $end = microtime(true);
        display( "Processing time : ".number_format($end - $begin, 2)." s\n");
        display( "Done\n");
    }
}

?>
