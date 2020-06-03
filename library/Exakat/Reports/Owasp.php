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

use Exakat\Analyzer\Analyzer;
use Exakat\Config;

class Owasp extends Ambassador {
    const FILE_FILENAME  = 'owasp';
    const FILE_EXTENSION = '';
    const CONFIG_YAML    = 'Owasp';

    const COLORS = array('A' => '#2ED600',
                         'B' => '#81D900',
                         'C' => '#D5DC00',
                         'D' => '#DF9100',
                         'E' => '#E23E00',
                         'F' => '#E50016',

                         );

    protected $analyzers       = array(); // cache for analyzers [Title] = object
    protected $projectPath     = null;
    protected $finalName       = null;

    protected $themesToShow    = array('Security');

    const TOPLIMIT = 10;
    const LIMITGRAPHE = 40;

    const NOT_RUN      = 'Not Run';
    const YES          = 'Yes';
    const NO           = 'No';
    const INCOMPATIBLE = 'Incompatible';

    private $components = array(
'A1:2017-Injection' => array(
    'Security/AnchorRegex',
    'Security/EncodedLetters',
    'Structures/EvalWithoutTry',
    'Security/parseUrlWithoutParameters',
    'Structures/pregOptionE',
    'Indirect Injection',
    'Security/IndirectInjection',
    'Structures/EvalUsage',
    'Security/Sqlite3RequiresSingleQuotes',
    'Security/ShouldUsePreparedStatement',
    'Security/FilterInputSource',
    'Security/NoEntIgnore',
),
'A2:2017-Broken Authentication' => array(

),
'A3:2017-Sensitive Data Exposure' => array(
    'Security/DontEchoError',
    'Structures/PhpinfoUsage',
    'Structures/VardumpUsage',
),
'A4:2017-XML External Entities (XXE)' => array(
    'Security/NoNetForXmlLoad',
),
'A5:2017-Broken Access Control' => array(
    'Structures/NoHardcodedHash',
    'Structures/NoHardcodedIp',
    'Structures/NoHardcodedPort',
    'Functions/HardcodedPasswords',
    'Security/CompareHash',
),
'A6:2017-Security Misconfiguration' => array(
    'Security/AvoidThoseCrypto',
    'Structures/RandomWithoutTry',
    'Security/CurlOptions',
    'Security/SetCookieArgs',
    'Security/ShouldUseSessionRegenerateId',
    'Security/SessionLazyWrite',
    'Php/BetterRand',
    'Security/MkdirDefault',
    'Security/RegisterGlobals',
    'Security/IntegerConversion',
    'Security/NoWeakSSLCrypto',
    'Security/MinusOneOnError',
    'Security/MoveUploadedFile',
    'Security/NoWeakSSLCrypto',
    'Security/KeepFilesRestricted',
),
'A7:2017-Cross-Site Scripting (XSS)' => array(
    'Security/UploadFilenameInjection',
),
'A8:2017-Insecure Deserialization' => array(
    'Security/UnserializeSecondArg',
    'Security/ConfigureExtract',
),
'A9:2017-Using Components with Known Vulnerabilities' => array(

),
'A10:2017-Insufficient Logging&Monitoring' => array(

),
'Others' => array(
    'Structures/NoReturnInFinally',
    'Security/NoSleep',
    'Structures/Fallthrough',
    'Security/DynamicDl',

));

    private function getLinesFromFile(string $filePath, int $lineNumber, int $numberBeforeAndAfter): array {
        --$lineNumber; // array index
        $lines = array();
        if (file_exists($this->config->projects_root . '/projects/' . $this->config->project . '/code/' . $filePath)) {

            $fileLines = file($this->config->projects_root . '/projects/' . $this->config->project . '/code/' . $filePath);

            $startLine = 0;
            $endLine = 10;
            if(count($fileLines) > $lineNumber) {
                $startLine = $lineNumber-$numberBeforeAndAfter;
                if($startLine<0)
                    $startLine=0;

                if($lineNumber+$numberBeforeAndAfter < count($fileLines)-1 ) {
                    $endLine = $lineNumber+$numberBeforeAndAfter;
                } else {
                    $endLine = count($fileLines)-1;
                }
            }

            for ($i=$startLine; $i < $endLine+1 ; ++$i) {
                $lines[]= array(
                            'line' => $i + 1,
                            'code' => $fileLines[$i]
                    );
            }
        }

        return $lines;
    }

    private function generateOwaspDocumentation(): void {
        $baseHTML = $this->getBasedPage('analyses_doc');

        $owasp = json_decode(file_get_contents($this->config->dir_root . '/data/owasp.top10.json'));

        $content = '<p>Documentation is extracted from the OWASP TOP 10 2017, with extra content from Exakat.</p><ul>';

        foreach($owasp as $doc) {
            $content.="<h2>$doc->code - $doc->name</h2>";
            $content .= "<p>$doc->description</p>\n";
            if (!empty($doc->url)) {
                $content .= "<p>Read more : <a target=\"_blank\" href=\"$doc->url\"><i class=\"fa fa-book\" style=\"font-size: 14px\"></i> $doc->name</a>.</p>";
            }
        }
        $content .= '</ul>';

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-ANALYZERS', $content);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/highlight.pack.js"></script>');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'OWASP\'s documentation');

        $this->putBasedPage('owasp_doc', $finalHTML);
    }

    protected function generateDetailledDashboard(Section $section): void {
        $levels = '';

        $countColors = count(self::COLORS);
        foreach($this->components as $group => $analyzers) {
            $levelRows = '';
            $total = 0;
            if (empty($analyzers)) {
                $levelRows .= "<tr><td>-</td><td>&nbsp;</td><td>-</td></tr>\n";
                $levels .= '<tr style="border-top: 3px solid black;"><td style="background-color: lightgrey">' . $group . '</td>
                            <td style="background-color: lightgrey">-</td></td>
                            <td style="background-color: lightgrey; font-weight: bold; font-size: 20; text-align: center"">N/A</td></tr>' . PHP_EOL .
                       $levelRows;
                continue;
            }

            $res = $this->dump->fetchAnalysersCounts($analyzers);
            $count = 0;
            foreach($res->toArray() as $row) {
                $ini = $this->docs->getDocs($row['analyzer']);

#FF0000	Bad
#FFFF00	Bad-Average
#FFFF00	Average
#7FFF00	Average-Good
#00FF00	Good

                if ($row['count'] == 0) {
                    $row['grade'] = 'A';
                } else {
                    $grade = intval(min(ceil(log($row['count'] + 1) / log($countColors)), $countColors - 1));
                    $row['grade'] = chr(66 + $grade - 1); // B to F
                }
                $row['color'] = self::COLORS[$row['grade']];

                $total += $row['count'];
                $count += (int) ($row['count'] === 0);

                $levelRows .= "<tr><td><a href=\"issues.html#analyzer={$this->toId($row['analyzer'])}\" title=\"$ini[name]\">$ini[name]</a></td><td>$row[count]</td><td style=\"background-color: $row[color]; color: white; font-weight: bold; font-size: 20; text-align: center; \">$row[grade]</td></tr>\n";
            }

            if ($total === 0) {
                $grade = 'A';
            } else {
                $grade = intval(min(ceil(log($total) / log($countColors)), $countColors - 1));
                $grade = chr(65 + $grade); // B to F
            }
            $color = self::COLORS[$grade];

            $levels .= '<tr style="border-top: 3px solid black;"><td style="background-color: lightgrey">' . $group . '</td>
                            <td style="background-color: lightgrey">' . $total . '</td></td>
                            <td style="background-color: ' . $color . '; font-weight: bold; font-size: 20; text-align: center; ">' . $grade . '</td></tr>' . PHP_EOL .
                       $levelRows;
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'LEVELS', $levels);
        $html = $this->injectBloc($html, 'TITLE', 'Overview for OWASP top 10');

        $this->putBasedPage($section->file, $html);
    }

    protected function generateDashboard(Section $section): void {
        $levels = '';

        $countColors = count(self::COLORS);
        foreach($this->components as $group => $analyzers) {
            $levelRows = '';
            $total = 0;
            if (empty($analyzers)) {
                continue;
            }
            $analyzersList = makeList($analyzers);

            $res = $this->dump->fetchAnalysersCounts($analyzers);
            $sources = array_filter($res->toHash('analyzer', 'count'), function (int $x): bool { return $x > -1;});
            asort($sources);

            $empty = 0;
            foreach($sources as $name => $count) {
#FF0000	Bad
#FFFF00	Bad-Average
#FFFF00	Average
#7FFF00	Average-Good
#00FF00	Good

                if ($count == 0) {
                    $grade = 'A';
                } else {
                    $grade = intval(min(ceil(log($count) / log($countColors)), $countColors - 1));
                    $grade = chr(66 + $grade - 1); // B to F
                }
                $color = self::COLORS[$grade];

                $total += $count;
                $empty += (int) $empty === 0;
            }

            if ($total === 0) {
                $grade = 'A';
            } else {
                $grade = intval(min(ceil(log($total) / log($countColors)), $countColors - 1));
                $grade = chr(65 + $grade); // B to F
            }
            $color = self::COLORS[$grade];

            $levels .= '<tr style="border-top: 3px solid black; border-bottom: 3px solid black;"><td style="background-color: lightgrey">' . $group . '</td>
                            <td style="background-color: lightgrey">&nbsp;</td></td>
                            <td style="background-color: ' . $color . '">' . $grade . '</td></tr>' . PHP_EOL;
        }

        $html = $this->getBasedPage($section->source);
        $html = $this->injectBloc($html, 'LEVELS', $levels);
        $html = $this->injectBloc($html, 'TITLE', $section->title);
        $this->putBasedPage($section->file, $html);
    }

    public function getHashData(): string {
        $php = exakat('php');

        $info = array(
            'Number of PHP files'                   => $this->datastore->getHash('files'),
            'Number of lines of code'               => $this->datastore->getHash('loc'),
            'Number of lines of code with comments' => $this->datastore->getHash('locTotal'),
            'PHP used' => $php->getConfiguration('phpversion') //.' (version '.$this->config->phpversion.' configured)'
        );

        // fichier
        $totalFile = $this->datastore->getHash('files');
        $totalFileAnalysed = $this->getTotalAnalysedFile();
        $totalFileSansError = $totalFileAnalysed - $totalFile;
        if ($totalFile === 0) {
            $percentFile = 100;
        } else {
            $percentFile = abs(round($totalFileSansError / $totalFile * 100));
        }

        // analyzer
        list($totalAnalyzerUsed, $totalAnalyzerReporting) = $this->getTotalAnalyzer();
        $totalAnalyzerWithoutError = $totalAnalyzerUsed - $totalAnalyzerReporting;
        $percentAnalyzer = abs(round($totalAnalyzerWithoutError / $totalAnalyzerUsed * 100));

        $html = '<div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Project Overview</h3>
                    </div>

                    <div class="box-body chart-responsive">
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span># of PHP</span> files</p>
                                <p class="value">' . $info['Number of PHP files'] . '</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> Used</p>
                                <p class="value">' . $info['PHP used'] . '</p>
                             </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> LoC</p>
                                <p class="value">' . $info['Number of lines of code'] . '</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>Total</span> LoC</p>
                                <p class="value">' . $info['Number of lines of code with comments'] . '</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <div class="title">Files free of issues (%)</div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentFile . '%">
                                        ' . $totalFileSansError . '
                                    </div><div style="color:black; text-align:center;">' . $totalFileAnalysed . '</div>
                                </div>
                                <div class="pourcentage">' . $percentFile . '%</div>
                            </div>
                            <div class="sub-div">
                                <div class="title">Analyses free of issues (%)</div>
                                <div class="progress progress-sm active">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percentAnalyzer . '%">
                                        ' . $totalAnalyzerWithoutError . '
                                    </div><div style="color:black; text-align:center;">' . $totalAnalyzerReporting . '</div>
                                </div>
                                <div class="pourcentage">' . $percentAnalyzer . '%</div>
                            </div>
                        </div>
                    </div>
                </div>';

        return $html;
    }

    protected function generateAnalyzers(): void {
        $analysers = $this->getAnalyzersResultsCounts();

        $baseHTML = $this->getBasedPage('analyzers');
        $analyserHTML = '';

        foreach ($analysers as $analyser) {
            $analyserHTML.= '<tr>';
            $analyserHTML.='<td>' . $analyser['label'] . '</td>
                        <td>' . $analyser['recipes'] . '</td>
                        <td>' . $analyser['issues'] . '</td>
                        <td>' . $analyser['files'] . '</td>
                        <td>' . $analyser['severity'] . '</td>';
            $analyserHTML.= '</tr>';
        }

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-ANALYZERS', $analyserHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/datatables.js"></script>');

        $this->putBasedPage('analyzers', $finalHTML);
    }

    protected function generateFiles(Section $section): void {
        $files = $this->getFilesResultsCounts();

        $baseHTML = $this->getBasedPage('files');
        $filesHTML = '';

        foreach ($files as $file) {
            $filesHTML.= '<tr>';
            $filesHTML.='<td>' . $file['file'] . '</td>
                        <td>' . $file['loc'] . '</td>
                        <td>' . $file['issues'] . '</td>
                        <td>' . $file['analyzers'] . '</td>';
            $filesHTML.= '</tr>';
        }

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-FILES', $filesHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/datatables.js"></script>');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Files\' list');

        $this->putBasedPage('files', $finalHTML);
    }

    protected function getFileOverview(): array {
        $data = $this->getFilesCount(self::LIMITGRAPHE);
        $xAxis        = array();
        $dataMajor    = array();
        $dataCritical = array();
        $dataNone     = array();
        $dataMinor    = array();
        $severities = $this->getSeveritiesNumberBy('file');
        foreach ($data as $value) {
            $xAxis[] = "'" . $value['file'] . "'";
            $dataCritical[] = empty($severities[$value['file']]['Critical']) ? 0 : $severities[$value['file']]['Critical'];
            $dataMajor[]    = empty($severities[$value['file']]['Major']) ? 0 : $severities[$value['file']]['Major'];
            $dataMinor[]    = empty($severities[$value['file']]['Minor']) ? 0 : $severities[$value['file']]['Minor'];
            $dataNone[]     = empty($severities[$value['file']]['None']) ? 0 : $severities[$value['file']]['None'];
        }
        $xAxis        = implode(', ', $xAxis);
        $dataCritical = implode(', ', $dataCritical);
        $dataMajor    = implode(', ', $dataMajor);
        $dataMinor    = implode(', ', $dataMinor);
        $dataNone     = implode(', ', $dataNone);

        return array(
            'scriptDataFiles'    => $xAxis,
            'scriptDataMajor'    => $dataMajor,
            'scriptDataCritical' => $dataCritical,
            'scriptDataNone'     => $dataNone,
            'scriptDataMinor'    => $dataMinor
        );
    }

    protected function getAnalyzerOverview(): array {
        $data = $this->getAnalyzersCount(self::LIMITGRAPHE);
        $xAxis        = array();
        $dataMajor    = array();
        $dataCritical = array();
        $dataNone     = array();
        $dataMinor    = array();

        $severities = $this->getSeveritiesNumberBy('analyzer');
        foreach ($data as $value) {
            $xAxis[] = "'" . $value['analyzer'] . "'";
            $dataCritical[] = empty($severities[$value['analyzer']]['Critical']) ? 0 : $severities[$value['analyzer']]['Critical'];
            $dataMajor[]    = empty($severities[$value['analyzer']]['Major']) ? 0 : $severities[$value['analyzer']]['Major'];
            $dataMinor[]    = empty($severities[$value['analyzer']]['Minor']) ? 0 : $severities[$value['analyzer']]['Minor'];
            $dataNone[]     = empty($severities[$value['analyzer']]['None']) ? 0 : $severities[$value['analyzer']]['None'];
        }
        $xAxis        = implode(', ', $xAxis);
        $dataCritical = implode(', ', $dataCritical);
        $dataMajor    = implode(', ', $dataMajor);
        $dataMinor    = implode(', ', $dataMinor);
        $dataNone     = implode(', ', $dataNone);

        return array(
            'scriptDataAnalyzer'         => $xAxis,
            'scriptDataAnalyzerMajor'    => $dataMajor,
            'scriptDataAnalyzerCritical' => $dataCritical,
            'scriptDataAnalyzerNone'     => $dataNone,
            'scriptDataAnalyzerMinor'    => $dataMinor
        );
    }

    protected function compatibility(int $count, string $analyzer): string {
        if ($count == Analyzer::VERSION_INCOMPATIBLE) {
            return '<i class="fa fa-ban"></i>';
        } elseif ($count == Analyzer::CONFIGURATION_INCOMPATIBLE) {
            return '<i class="fa fa-cogs"></i>';
        } elseif ($count === 0) {
            return '<i class="fa fa-check-square-o"></i>';
        } else {
            return '<i class="fa fa-warning red"></i>&nbsp;' . $count . ' warnings';
        }
    }

    protected function makeAuditDate(string &$finalHTML): void {
        $audit_date = 'Audit date : ' . date('d-m-Y h:i:s', time());
        $audit_name = $this->datastore->getHash('audit_name');
        if (!empty($audit_name)) {
            $audit_date .= ' - &quot;' . $audit_name . '&quot;';
        }
        $finalHTML = $this->injectBloc($finalHTML, 'AUDIT_DATE', $audit_date);
    }

    public function dependsOnAnalysis(): array {
        return array('Security',
                     );
    }

}

?>