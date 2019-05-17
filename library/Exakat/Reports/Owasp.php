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
use Exakat\Config;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;

class Owasp extends Ambassador {
    const FILE_FILENAME  = 'owasp';
    const FILE_EXTENSION = '';

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
    protected $tmpName           = '';

    private $timesToFix        = null;
    private $themesForAnalyzer = null;
    private $severities        = null;

    const TOPLIMIT = 10;
    const LIMITGRAPHE = 40;

    const NOT_RUN      = 'Not Run';
    const YES          = 'Yes';
    const NO           = 'No';
    const INCOMPATIBLE = 'Incompatible';

    private $inventories = array('constants'  => 'Constants',
                                 'classes'    => 'Classes',
                                 'interfaces' => 'Interfaces',
                                 'functions'  => 'Functions',
                                 'traits'     => 'Traits',
                                 'namespaces' => 'Namespaces',
                                 'exceptions' => 'Exceptions');

    private $compatibilities = array();

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
    'Security/ShouldUsePreparedStatement',
    'Security/ShouldUseSessionRegenerateId',
    'Security/SessionLazyWrite',
    'Php/BetterRand',
    'Security/MkdirDefault',
    'Security/RegisterGlobals',
),
'A7:2017-Cross-Site Scripting (XSS)' => array(
    'Security/UploadFilenameInjection',
),
'A8:2017-Insecure Deserialization' => array(
    'Security/UnserializeSecondArg',
),
'A9:2017-Using Components with Known Vulnerabilities' => array(
),
'A10:2017-Insufficient Logging&Monitoring' => array(
),
'Others' => array(
    'Structures/NoReturnInFinally',
    'Security/NoSleep',
    'Structures/Fallthrough',
));

    public function __construct($config) {
        parent::__construct($config);

        foreach(Config::PHP_VERSIONS as $shortVersion) {
            $this->compatibilities[$shortVersion] = "Compatibility PHP $shortVersion[0].$shortVersion[1]";
        }

        if ($this->themes !== null) {
            $this->themesToShow      = array('Security');
            $this->timesToFix        = $this->themes->getTimesToFix();
            $this->themesForAnalyzer = $this->themes->getThemesForAnalyzer($this->themesToShow);
            $this->severities        = $this->themes->getSeverities();
        }
    }

    protected function getBasedPage($file) {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents($this->config->dir_root . '/media/devfaceted/datas/base.html');

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $this->config->project);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($this->config->project{0}));

            $menu = <<<'MENU'
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
          <li class="header">&nbsp;</li>
          <!-- Optionally, you can add icons to the links -->
          <li class="active"><a href="index.html"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
          <li><a href="detailled.html"><i class="fa fa-flag"></i> <span>Detailled</span></a></li>
          <li><a href="issues.html"><i class="fa fa-flag"></i> <span>Issues</span></a></li>
          <li class="treeview">
            <a href="#"><i class="fa fa-sticky-note-o"></i> <span>Annexes</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="annex_settings.html"><i class="fa fa-circle-o"></i>Analyzer Settings</a></li>
              <li><a href="analyzers_doc.html"><i class="fa fa-circle-o"></i>Analyzers Documentation</a></li>
              <li><a href="owasp_doc.html"><i class="fa fa-circle-o"></i>Owasp Documentation</a></li>
              <li><a href="codes.html"><i class="fa fa-circle-o"></i>Codes</a></li>
              <li><a href="credits.html"><i class="fa fa-circle-o"></i>Credits</a></li>
            </ul>
          </li>
        </ul>
        <!-- /.sidebar-menu -->
MENU;

            $baseHTML = $this->injectBloc($baseHTML, 'SIDEBARMENU', $menu);
        }

        $subPageHTML = file_get_contents($this->config->dir_root . '/media/devfaceted/datas/' . $file . '.html');
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }

    public function generate($folder, $name = 'report') {
        if ($name === self::STDOUT) {
            print "Can't produce Ambassador format to stdout\n";
            return false;
        }
        
        $this->finalName = "$folder/$name";
        $this->tmpName   = "{$this->config->tmp_dir}/.$name";

        $this->projectPath = $folder;

        $this->initFolder();
        $this->generateProcFiles();

        $this->generateDashboard();
        $this->generateDetailledDashboard();
        $this->generateIssues();

        // Annex
        $this->generateAnalyzerSettings();
        $this->generateOwaspDocumentation();
        $this->generateDocumentation($this->themes->getThemeAnalyzers($this->themesToShow));
        $this->generateCodes();

        // Static files
        $files = array('credits');
        foreach($files as $file) {
            $baseHTML = $this->getBasedPage($file);
            $this->putBasedPage($file, $baseHTML);
        }

        $this->cleanFolder();
    }

    protected function cleanFolder() {
        if (file_exists($this->tmpName . '/datas/base.html')) {
            unlink($this->tmpName . '/datas/base.html');
            unlink($this->tmpName . '/datas/menu.html');
        }

        // Clean final destination
        if ($this->finalName !== '/') {
            rmdirRecursive($this->finalName);
        }

        if (file_exists($this->finalName)) {
            display($this->finalName . " folder was not cleaned. Please, remove it before producing the report. Aborting report\n");
            return;
        }

        rename($this->tmpName, $this->finalName);
    }

    private function getLinesFromFile($filePath,$lineNumber,$numberBeforeAndAfter) {
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

    private function generateOwaspDocumentation() {
        $baseHTML = $this->getBasedPage('analyzers_doc');
        
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

    protected function generateDashboard() {
        $levels = '';

        foreach($this->components as $section => $analyzers) {
            $levelRows = '';
            $total = 0;
            if (empty($analyzers)) {
                $levelRows .= "<tr><td>-</td><td>&nbsp;</td><td>-</td></tr>\n";
                $levels .= '<tr style="border-top: 3px solid black;"><td style="background-color: lightgrey">' . $section . '</td>
                            <td style="background-color: lightgrey">-</td></td>
                            <td style="background-color: lightgrey; font-weight: bold; font-size: 20; text-align: center"">N/A</td></tr>' . PHP_EOL .
                       $levelRows;
                continue;
            }
            $analyzersList = makeList($analyzers);
        
            $res = $this->sqlite->query(<<<SQL
SELECT analyzer AS name, count FROM resultsCounts WHERE analyzer in ($analyzersList) AND count >= 0 ORDER BY count
SQL
);
            $count = 0;
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $ini = $this->getDocs($row['name']);

#FF0000	Bad
#FFFF00	Bad-Average
#FFFF00	Average
#7FFF00	Average-Good
#00FF00	Good

                if ($row['count'] == 0) {
                    $row['grade'] = 'A';
                } else {
                    $grade = min(ceil(log($row['count'] + 1) / log(count(self::COLORS))), count(self::COLORS) - 1);
                    $row['grade'] = chr(66 + $grade - 1); // B to F
                }
                $row['color'] = self::COLORS[$row['grade']];
                
                $total += $row['count'];
                $count += (int) ($row['count'] === 0);

                $levelRows .= "<tr><td><a href=\"issues.html#analyzer={$this->toId($row['name'])}\" title=\"$ini[name]\">$ini[name]</a></td><td>$row[count]</td><td style=\"background-color: $row[color]; color: white; font-weight: bold; font-size: 20; text-align: center; \">$row[grade]</td></tr>\n";
            }

            if ($total === 0) {
                $grade = 'A';
            } else {
                $grade = min(ceil(log($total) / log(count(self::COLORS))), count(self::COLORS) - 1);
                $grade = chr(65 + $grade); // B to F
            }
            $color = self::COLORS[$grade];
            
            $levels .= '<tr style="border-top: 3px solid black;"><td style="background-color: lightgrey">' . $section . '</td>
                            <td style="background-color: lightgrey">' . $total . '</td></td>
                            <td style="background-color: ' . $color . '; font-weight: bold; font-size: 20; text-align: center; ">' . $grade . '</td></tr>' . PHP_EOL .
                       $levelRows;
        }

        $html = $this->getBasedPage('levels');
        $html = $this->injectBloc($html, 'LEVELS', $levels);
        $html = $this->injectBloc($html, 'TITLE', 'Overview for OWASP top 10');
        $this->putBasedPage('detailled', $html);
    }

    protected function generateDetailledDashboard() {
        $levels = '';

        foreach($this->components as $section => $analyzers) {
            $levelRows = '';
            $total = 0;
            if (empty($analyzers)) {
                continue;
            }
            $analyzersList = makeList($analyzers);
        
            $res = $this->sqlite->query(<<<SQL
SELECT analyzer AS name, count FROM resultsCounts WHERE analyzer in ($analyzersList) AND count >= 0 ORDER BY count
SQL
);
            $count = 0;
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $ini = $this->getDocs($row['name']);

#FF0000	Bad
#FFFF00	Bad-Average
#FFFF00	Average
#7FFF00	Average-Good
#00FF00	Good

                if ($row['count'] == 0) {
                    $row['grade'] = 'A';
                } else {
                    $grade = min(ceil(log($row['count']) / log(count(self::COLORS))), count(self::COLORS) - 1);
                    $row['grade'] = chr(66 + $grade - 1); // B to F
                }
                $row['color'] = self::COLORS[$row['grade']];
                
                $total += $row['count'];
                $count += (int) $row['count'] === 0;
            }

            if ($total === 0) {
                $grade = 'A';
            } else {
                $grade = min(ceil(log($total) / log(count(self::COLORS))), count(self::COLORS) - 1);
                $grade = chr(65 + $grade); // B to F
            }
            $color = self::COLORS[$grade];
            
            $levels .= '<tr style="border-top: 3px solid black; border-bottom: 3px solid black;"><td style="background-color: lightgrey">' . $section . '</td>
                            <td style="background-color: lightgrey">&nbsp;</td></td>
                            <td style="background-color: ' . $color . '">' . $grade . '</td></tr>' . PHP_EOL;
        }

        $html = $this->getBasedPage('levels');
        $html = $this->injectBloc($html, 'LEVELS', $levels);
        $html = $this->injectBloc($html, 'TITLE', 'Detailled sections for OWASP top 10');
        $this->putBasedPage('index', $html);
    }

    public function getHashData() {
        $php = new Phpexec($this->config->phpversion, $this->config->{'php' . str_replace('.', '', $this->config->phpversion)});

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
                                <div class="title">Analyzers free of issues (%)</div>
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

    public function getIssuesBreakdown() {
        $receipt = array('Code Smells'  => 'Analyze',
                         'Dead Code'    => 'Dead code',
                         'Security'     => 'Security',
                         'Performances' => 'Performances');

        $data = array();
        foreach ($receipt AS $key => $categorie) {
            $list = 'IN ("' . implode('", "', $this->themes->getThemeAnalyzers($categorie)) . '")';
            $query = "SELECT sum(count) FROM resultsCounts WHERE analyzer $list AND count > 0";
            $total = $this->sqlite->querySingle($query);

            $data[] = array('label' => $key, 'value' => $total);
        }
        // ordonné DESC par valeur
        uasort($data, function ($a, $b) {
            if ($a['value'] > $b['value']) {
                return -1;
            } elseif ($a['value'] < $b['value']) {
                return 1;
            } else {
                return 0;
            }
        });
        $issuesHtml = '';
        $dataScript = '';

        foreach ($data as $value) {
            $issuesHtml .= '<div class="clearfix">
                   <div class="block-cell">' . $value['label'] . '</div>
                   <div class="block-cell text-center">' . $value['value'] . '</div>
                 </div>';
            $dataScript .= $dataScript ? ', {label: "' . $value['label'] . '", value: ' . $value['value'] . '}' : '{label: "' . $value['label'] . '", value: ' . $value['value'] . '}';
        }
        $nb = 4 - count($data);
        $filler = '<div class="clearfix">
               <div class="block-cell">&nbsp;</div>
               <div class="block-cell text-center">&nbsp;</div>
             </div>';
        $issuesHtml .= str_repeat($filler, $nb);

        return array('html'   => $issuesHtml,
                     'script' => $dataScript);
    }

    public function getSeverityBreakdown() {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = '"' . implode('", "', $list) . '"';

        $query = <<<SQL
                SELECT severity, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY severity
                    ORDER BY number DESC
SQL;
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = array('label' => $row['severity'],
                            'value' => $row['number']);
        }

        $html = '';
        $dataScript = '';
        foreach ($data as $value) {
            $html .= '<div class="clearfix">
                   <div class="block-cell">' . $value['label'] . '</div>
                   <div class="block-cell text-center">' . $value['value'] . '</div>
                 </div>';
            $dataScript .= $dataScript ? ', {label: "' . $value['label'] . '", value: ' . $value['value'] . '}' : '{label: "' . $value['label'] . '", value: ' . $value['value'] . '}';
        }
        $nb = 4 - count($data);
        $filler = '<div class="clearfix">
               <div class="block-cell">&nbsp;</div>
               <div class="block-cell text-center">&nbsp;</div>
             </div>';
        $html .= str_repeat($filler, $nb);

        return array('html' => $html, 'script' => $dataScript);
    }

    protected function getTotalAnalysedFile() {
        $query = 'SELECT COUNT(DISTINCT file) FROM results';
        $result = $this->sqlite->query($query);

        $result = $result->fetchArray(\SQLITE3_NUM);
        return $result[0];
    }

    protected function getTotalAnalyzer($issues = false) {
        $query = 'SELECT count(*) AS total, COUNT(CASE WHEN rc.count != 0 THEN 1 ELSE null END) AS yielding 
            FROM resultsCounts AS rc
            WHERE rc.count >= 0';

        $stmt = $this->sqlite->prepare($query);
        $result = $stmt->execute();

        return $result->fetchArray(\SQLITE3_NUM);
    }

    protected function generateAnalyzers() {
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

    protected function getAnalyzersResultsCounts() {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = '"' . implode('", "', $list) . '"';

        $result = $this->sqlite->query(<<<SQL
        SELECT analyzer, count(*) AS issues, count(distinct file) AS files, severity AS severity FROM results
        WHERE analyzer IN ($list)
        GROUP BY analyzer
        HAVING Issues > 0
SQL
        );

        $return = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $row['label'] = $this->getDocs($row['analyzer'], 'name');
            $row['recipes' ] =  implode(', ', $this->themesForAnalyzer[$row['analyzer']]);

            $return[] = $row;
        }

        return $return;
    }

    private function getCountFileByAnalyzers($analyzer) {
        $query = <<<'SQL'
                SELECT count(*)  AS number
                FROM (SELECT DISTINCT file FROM results WHERE analyzer = :analyzer)
SQL;
        $stmt = $this->sqlite->prepare($query);
        $stmt->bindValue(':analyzer', $analyzer, \SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(\SQLITE3_ASSOC);

        return $row['number'];
    }

    protected function generateFiles() {
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

    private function getFilesResultsCounts() {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = '"' . implode('", "', $list) . '"';

        $result = $this->sqlite->query(<<<'SQL'
SELECT file AS file, line AS loc, count(*) AS issues, count(distinct analyzer) AS analyzers 
    FROM results
    WHERE analyzer IN ($list)
    GROUP BY file
SQL
        );
        $return = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['file']] = $row;
        }

        return $return;
    }

    private function getCountAnalyzersByFile($file) {
        $query = <<<'SQL'
                SELECT count(*)  AS number
                FROM (SELECT DISTINCT analyzer FROM results WHERE file = :file)
SQL;
        $stmt = $this->sqlite->prepare($query);
        $stmt->bindValue(':file', $file, \SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(\SQLITE3_ASSOC);

        return $row['number'];
    }

    protected function getFileOverview() {
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
            $dataMajor[]    = empty($severities[$value['file']]['Major'])    ? 0 : $severities[$value['file']]['Major'];
            $dataMinor[]    = empty($severities[$value['file']]['Minor'])    ? 0 : $severities[$value['file']]['Minor'];
            $dataNone[]     = empty($severities[$value['file']]['None'])     ? 0 : $severities[$value['file']]['None'];
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

    protected function getAnalyzersCount($limit) {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = '"' . implode('", "', $list) . '"';

        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    WHERE analyzer in ($list)
                    GROUP BY analyzer
                    ORDER BY number DESC ";
        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = array('analyzer' => $row['analyzer'],
                            'value'    => $row['number']);
        }

        return $data;
    }

    protected function getSeveritiesNumberBy($type = 'file') {
        $list = $this->themes->getThemeAnalyzers($this->themesToShow);
        $list = makeList($list);

        $query = <<<SQL
SELECT $type, severity, count(*) AS count
    FROM results
    WHERE analyzer IN ($list)
    GROUP BY $type, severity
SQL;

        $stmt = $this->sqlite->query($query);

        $return = array();
        while ($row = $stmt->fetchArray(\SQLITE3_ASSOC) ) {
            if (isset($return[$row[$type]]) ) {
                $return[$row[$type]][$row['severity']] = $row['count'];
            } else {
                $return[$row[$type]] = array($row['severity'] => $row['count']);
            }
        }

        return $return;
    }

    protected function getAnalyzerOverview() {
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
            $dataMajor[]    = empty($severities[$value['analyzer']]['Major'])    ? 0 : $severities[$value['analyzer']]['Major'];
            $dataMinor[]    = empty($severities[$value['analyzer']]['Minor'])    ? 0 : $severities[$value['analyzer']]['Minor'];
            $dataNone[]     = empty($severities[$value['analyzer']]['None'])     ? 0 : $severities[$value['analyzer']]['None'];
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

    private function generateIssues() {
        $this->generateIssuesEngine('issues',
                                    $this->getIssuesFaceted('Security'));
        return;
    }

    protected function generateCompatibility($version) {
        $compatibility = '';

        $list = $this->themes->getThemeAnalyzers('CompatibilityPHP' . $version);

        $res = $this->sqlite->query('SELECT analyzer, counts FROM analyzed');
        $counts = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[$row['analyzer']] = $row['counts'];
        }

        foreach($list as $l) {
            $ini = $this->getDocs($l);
            if (isset($counts[$l])) {
                $result = (int) $counts[$l];
            } else {
                $result = -1;
            }
            $result = $this->Compatibility($result);
            $name = $ini['name'];
            $link = '<a href="analyzers_doc.html#' . $this->toId($name) . '" alt="Documentation for $name"><i class="fa fa-book"></i></a>';
            $compatibility .= "<tr><td>$name $link</td><td>$result</td></tr>\n";
        }

        $description = <<<'HTML'
<i class="fa fa-check-square-o"></i> : Nothing found for this analysis, proceed with caution; <i class="fa fa-warning red"></i> : some issues found, check this; <i class="fa fa-ban"></i> : Can't test this, PHP version incompatible; <i class="fa fa-cogs"></i> : Can't test this, PHP configuration incompatible; 
HTML;

        $html = $this->getBasedPage('compatibility');
        $html = $this->injectBloc($html, 'COMPATIBILITY', $compatibility);
        $html = $this->injectBloc($html, 'TITLE', 'Compatibility PHP ' . $version[0] . '.' . $version[1]);
        $html = $this->injectBloc($html, 'DESCRIPTION', $description);
        $this->putBasedPage('compatibility_php' . $version, $html);
    }

    private function generateDynamicCode() {
        $dynamicCode = '';

        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Structures/DynamicCode"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $dynamicCode .= '<tr><td>' . PHPSyntax($row['fullcode']) . "</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
        }

        $html = $this->getBasedPage('dynamic_code');
        $html = $this->injectBloc($html, 'DYNAMIC_CODE', $dynamicCode);
        $this->putBasedPage('dynamic_code', $html);
    }

    protected function makeIcon($tag) {
        switch($tag) {
            case self::YES :
                return '<i class="fa fa-check-square-o"></i>';
            case self::NO :
                return '<i class="fa fa-square-o"></i>';
            case self::NOT_RUN :
                return '<i class="fa fa-hourglass-o"></i>';
            case self::INCOMPATIBLE :
                return '<i class="fa fa-remove"></i>';
            default :
                return '&nbsp;';
        }
    }

    private function Compatibility($count) {
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
    
    protected function makeAuditDate(&$finalHTML) {
        $audit_date = 'Audit date : ' . date('d-m-Y h:i:s', time());
        $audit_name = $this->datastore->getHash('audit_name');
        if (!empty($audit_name)) {
            $audit_date .= ' - &quot;' . $audit_name . '&quot;';
        }
        $finalHTML = $this->injectBloc($finalHTML, 'AUDIT_DATE', $audit_date);
    }

    public function dependsOnAnalysis() {
        return array('Security',
                     );
    }

}

?>