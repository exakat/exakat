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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Docs;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;

class Owasp extends Reports {
    const FILE_FILENAME  = 'report_owasp';

    protected $analyzers       = array(); // cache for analyzers [Title] = object
    protected $projectPath     = null;
    protected $finalName       = null;
    private $tmpName           = '';

    private $docs              = null;
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

    private $compatibilities = array('53' => 'Compatibility PHP 5.3',
                                     '54' => 'Compatibility PHP 5.4',
                                     '55' => 'Compatibility PHP 5.5',
                                     '56' => 'Compatibility PHP 5.6',
                                     '70' => 'Compatibility PHP 7.0',
                                     '71' => 'Compatibility PHP 7.1',
                                     '72' => 'Compatibility PHP 7.2',
                                     '73' => 'Compatibility PHP 7.3',
                                     );

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
        $this->themesToShow      = 'Security';
        $this->docs              = new Docs($this->config->dir_root.'/data/analyzers.sqlite');
        $this->timesToFix        = $this->docs->getTimesToFix();
        $this->themesForAnalyzer = $this->docs->getThemesForAnalyzer($this->themesToShow);
        $this->severities        = $this->docs->getSeverities();
    }

    private function getBasedPage($file) {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/base.html');
            $title = ($file == 'index') ? 'Dashboard' : $file;

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $this->config->project);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($this->config->project{0}));

            $menu = <<<MENU
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

        $subPageHTML = file_get_contents($this->config->dir_root.'/media/devfaceted/datas/'.$file.'.html');
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }

    private function putBasedPage($file, $html) {
        if (strpos($html, '{{BLOC-JS}}') !== false) {
            $html = str_replace('{{BLOC-JS}}', '', $html);
        }
        $html = str_replace('{{TITLE}}', 'PHP Static analysis for '.$this->config->project, $html);

        file_put_contents($this->tmpName.'/datas/'.$file.'.html', $html);
    }

    private function injectBloc($html, $bloc, $content) {
        return str_replace("{{".$bloc."}}", $content, $html);
    }

    public function generate($folder, $name = 'report') {
        $this->finalName = $folder.'/'.$name;
        $this->tmpName = $folder.'/.'.$name;

        $this->projectPath = $folder;

        $this->initFolder();
        $this->generateSettings();
        $this->generateProcFiles();

        $this->generateDashboard();
        $this->generateDetailledDashboard();
        $this->generateIssues();

        // Annex
        $this->generateAnalyzerSettings();
        $this->generateOwaspDocumentation();
        $this->generateDocumentation();
        $this->generateCodes();

        // Static files
        $files = array('credits');
        foreach($files as $file) {
            $baseHTML = $this->getBasedPage($file);
            $this->putBasedPage($file, $baseHTML);
        }

        $this->cleanFolder();
    }

    private function initFolder() {
        if ($this->finalName === Reports::STDOUT) {
            return "Can't produce this report format to stdout. It needs a file name.";
        }

        // Clean temporary destination
        if (file_exists($this->tmpName)) {
            rmdirRecursive($this->tmpName);
        }

        // Copy template
        copyDir($this->config->dir_root.'/media/devfaceted', $this->tmpName );
    }

    private function cleanFolder() {
        if (file_exists($this->tmpName.'/datas/base.html')) {
            unlink($this->tmpName.'/datas/base.html');
            unlink($this->tmpName.'/datas/menu.html');
        }

        // Clean final destination
        if ($this->finalName !== '/') {
            rmdirRecursive($this->finalName);
        }

        if (file_exists($this->finalName)) {
            display($this->finalName." folder was not cleaned. Please, remove it before producing the report. Aborting report\n");
            return;
        }

        rename($this->tmpName, $this->finalName);
    }

    private function getLinesFromFile($filePath,$lineNumber,$numberBeforeAndAfter){
        --$lineNumber; // array index
        $lines = array();
        if (file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/code/'.$filePath)) {

            $fileLines = file($this->config->projects_root.'/projects/'.$this->config->project.'/code/'.$filePath);

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

    private function setPHPBlocs($description){
        $description = str_replace("<?php", '</p><pre><code class="php">&lt;?php', $description);
        $description = str_replace("?>", '?&gt;</code></pre><p>', $description);
        return $description;
    }

    private function generateOwaspDocumentation(){
        $baseHTML = $this->getBasedPage('analyzers_doc');
        
        $owasp = json_decode(file_get_contents($this->config->dir_root.'/data/owasp.top10.json'));
        
        $content = '<p>Documentation is extracted from the OWASP TOP 10 2017, with extra content from Exakat.</p><ul>';
        
        foreach($owasp as $section => $doc) {
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

    private function generateDocumentation(){
        $datas = array();
        $baseHTML = $this->getBasedPage('analyzers_doc');
        $analyzersDocHTML = "";

        foreach(Analyzer::getThemeAnalyzers($this->themesToShow) as $analyzer) {
            $analyzer = Analyzer::getInstance($analyzer, null, $this->config);
            $description = $analyzer->getDescription();
            $analyzersDocHTML.='<h2><a href="issues.html?analyzer='.md5($description->getName()).'" id="'.md5($description->getName()).'">'.$description->getName().'</a></h2>';

            $badges = array();
            $v = $description->getVersionAdded();
            if(!empty($v)){
                $badges[] = '[Since '.$v.']';
            }
            $badges[] = '[ -P '.$analyzer->getInBaseName().' ]';

            $versionCompatibility = $analyzer->getPhpversion();
            if ($versionCompatibility !== Analyzer::PHP_VERSION_ANY) {
                if (strpos($versionCompatibility, '+') !== false) {
                    $versionCompatibility = substr($versionCompatibility, 0, -1).' and more recent ';
                } elseif (strpos($versionCompatibility, '-') !== false) {
                    $versionCompatibility = ' older than '.substr($versionCompatibility, 0, -1);
                }
                $badges[] = '[ PHP '.$versionCompatibility.']';
            }

            $analyzersDocHTML .= '<p>'.implode(' - ', $badges).'</p>';
            $analyzersDocHTML.='<p>'.$this->setPHPBlocs($description->getDescription()).'</p>';

            $v = $description->getClearPHP();
            if(!empty($v)){
                $analyzersDocHTML.='<p>This rule is named <a target="_blank" href="https://github.com/dseguy/clearPHP/blob/master/rules/'.$description->getClearPHP().'.md">'.$description->getClearPHP().'</a>, in the clearPHP reference.</p>';
            }
        }
        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-ANALYZERS', $analyzersDocHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/highlight.pack.js"></script>');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Analyzers\' documentation');

        $this->putBasedPage('analyzers_doc', $finalHTML);
    }

    protected function generateDashboard() {
        $levels = '';

        foreach($this->components as $section => $analyzers) {
            $levelRows = '';
            $total = 0;
            if (empty($analyzers)) {
                $levelRows .= "<tr><td>Nothing here</td><td>&nbsp;</td><td style=\"background-color: $row[color]\">-</td></tr>\n";
                $levels .= '<tr><td style="background-color: #bbbbbb">'.$section.'</td>
                            <td style="background-color: #bbbbbb">N/A</td></td>
                            <td >N/A</td></tr>'.PHP_EOL.
                       $levelRows;
                continue;
            }
            $analyzersList = makeList($analyzers);
        
            $res = $this->sqlite->query(<<<SQL
SELECT analyzer AS name, count FROM resultsCounts WHERE analyzer in ($analyzersList) ORDER BY count
SQL
);
            $colors = array('A' => '#00FF00',
                            'B' => '#32CC00',
                            'C' => '#669900',
                            'D' => '#996600',
                            'E' => '#CC3300',
                            'F' => '#FF0000',
                            );
            $count = 0;
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$row['name'].'.ini');

#FF0000	Bad
#FFFF00	Bad-Average
#FFFF00	Average
#7FFF00	Average-Good
#00FF00	Good

                if ($row['count'] == 0) {
                    $row['grade'] = 'A';
                } else {
                    $grade = min(ceil(log($row['count']) / log(count($colors))), count($colors) - 1);
                    $row['grade'] = chr(66 + $grade - 1); // B to F
                }
                $row['color'] = $colors[$row['grade']];
                
                $total += $row['count'];
                $count += (int) $row['count'] === 0;

                $levelRows .= "<tr><td><a href=\"issues.html#analyzer=$row[name]\" title=\"$ini[name]\">$ini[name]</a></td><td>$row[count]</td><td style=\"background-color: $row[color]\">$row[grade]</td></tr>\n";
            }

            $grade = floor($count / (count($analyzers)) * (count($colors) - 1));
            $grade = chr(65 + $grade); // B to F
            $color = $colors[$grade];
            
            $levels .= '<tr><td style="background-color: #bbbbbb">'.$section.'</td>
                            <td style="background-color: #bbbbbb">'.$total.'</td></td>
                            <td style="background-color: '.$color.'">'.$grade.'</td></tr>'.PHP_EOL.
                       $levelRows;
        }

        $html = $this->getBasedPage('levels');
        $html = $this->injectBloc($html, 'LEVELS', $levels);
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
SELECT analyzer AS name, count FROM resultsCounts WHERE analyzer in ($analyzersList) ORDER BY count
SQL
);
            $colors = array('A' => '#00FF00',
                            'B' => '#32CC00',
                            'C' => '#669900',
                            'D' => '#996600',
                            'E' => '#CC3300',
                            'F' => '#FF0000',
                            );
            $count = 0;
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$row['name'].'.ini');

#FF0000	Bad
#FFFF00	Bad-Average
#FFFF00	Average
#7FFF00	Average-Good
#00FF00	Good

                if ($row['count'] == 0) {
                    $row['grade'] = 'A';
                } else {
                    $grade = min(ceil(log($row['count']) / log(count($colors))), count($colors) - 1);
                    $row['grade'] = chr(66 + $grade - 1); // B to F
                }
                $row['color'] = $colors[$row['grade']];
                
                $total += $row['count'];
                $count += (int) $row['count'] === 0;
            }

            $grade = floor($count / (count($analyzers)) * (count($colors) - 1));
            $grade = chr(65 + $grade); // B to F
            $color = $colors[$grade];
            
            $levels .= '<tr><td style="background-color: #bbbbbb">'.$section.'</td>
                            <td style="background-color: #bbbbbb">&nbsp;</td></td>
                            <td style="background-color: '.$color.'">'.$grade.'</td></tr>'.PHP_EOL;
        }

        $html = $this->getBasedPage('levels');
        $html = $this->injectBloc($html, 'LEVELS', $levels);
        $this->putBasedPage('index', $html);
    }

    public function getHashData() {
        $php = new Phpexec($this->config->phpversion, $this->config->{'php'.str_replace('.', '', $this->config->phpversion)});

        $info = array(
            'Number of PHP files'                   => $this->datastore->getHash('files'),
            'Number of lines of code'               => $this->datastore->getHash('loc'),
            'Number of lines of code with comments' => $this->datastore->getHash('locTotal'),
            'PHP used' => $php->getActualVersion() //.' (version '.$this->config->phpversion.' configured)'
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
                                <p class="value">'.$info['Number of PHP files'].'</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> Used</p>
                                <p class="value">'.$info['PHP used'].'</p>
                             </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <p class="title"><span>PHP</span> LoC</p>
                                <p class="value">'.$info['Number of lines of code'].'</p>
                            </div>
                            <div class="sub-div">
                                <p class="title"><span>Total</span> LoC</p>
                                <p class="value">'.$info['Number of lines of code with comments'].'</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="sub-div">
                                <div class="title">Files free of issues (%)</div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: '.$percentFile.'%">
                                        '.$totalFileSansError.'
                                    </div><div style="color:black; text-align:center;">'.$totalFileAnalysed.'</div>
                                </div>
                                <div class="pourcentage">'.$percentFile.'%</div>
                            </div>
                            <div class="sub-div">
                                <div class="title">Analyzers free of issues (%)</div>
                                <div class="progress progress-sm active">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: '.$percentAnalyzer.'%">
                                        '.$totalAnalyzerWithoutError.'
                                    </div><div style="color:black; text-align:center;">'.$totalAnalyzerReporting.'</div>
                                </div>
                                <div class="pourcentage">'.$percentAnalyzer.'%</div>
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
            $list = 'IN ("'.implode('", "', Analyzer::getThemeAnalyzers($categorie)).'")';
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

        foreach ($data as $key => $value) {
            $issuesHtml .= '<div class="clearfix">
                   <div class="block-cell">'.$value['label'].'</div>
                   <div class="block-cell text-center">'.$value['value'].'</div>
                 </div>';
            $dataScript .= $dataScript ? ', {label: "'.$value['label'].'", value: '.$value['value'].'}' : '{label: "'.$value['label'].'", value: '.$value['value'].'}';
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
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.implode('", "', $list).'"';

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
        foreach ($data as $key => $value) {
            $html .= '<div class="clearfix">
                   <div class="block-cell">'.$value['label'].'</div>
                   <div class="block-cell text-center">'.$value['value'].'</div>
                 </div>';
            $dataScript .= $dataScript ? ', {label: "'.$value['label'].'", value: '.$value['value'].'}' : '{label: "'.$value['label'].'", value: '.$value['value'].'}';
        }
        $nb = 4 - count($data);
        $filler = '<div class="clearfix">
               <div class="block-cell">&nbsp;</div>
               <div class="block-cell text-center">&nbsp;</div>
             </div>';
        $html .= str_repeat($filler, $nb);

        return array('html' => $html, 'script' => $dataScript);
    }

    private function getTotalAnalysedFile() {
        $query = "SELECT COUNT(DISTINCT file) FROM results";
        $result = $this->sqlite->query($query);

        $result = $result->fetchArray(\SQLITE3_NUM);
        return $result[0];
    }

    private function getTotalAnalyzer($issues = false) {
        $query = "SELECT count(*) AS total, COUNT(CASE WHEN rc.count != 0 THEN 1 ELSE null END) AS yielding 
            FROM resultsCounts AS rc
            WHERE rc.count >= 0";

        $stmt = $this->sqlite->prepare($query);
        $result = $stmt->execute();

        return $result->fetchArray(\SQLITE3_NUM);
    }

    private function generateAnalyzers() {
        $analysers = $this->getAnalyzersResultsCounts();

        $baseHTML = $this->getBasedPage('analyzers');
        $analyserHTML = '';

        foreach ($analysers as $analyser) {
            $analyserHTML.= "<tr>";
            $analyserHTML.='<td>'.$analyser['label'].'</td>
                        <td>'.$analyser['recipes'].'</td>
                        <td>'.$analyser['issues'].'</td>
                        <td>'.$analyser['files'].'</td>
                        <td>'.$analyser['severity'].'</td>';
            $analyserHTML.= "</tr>";
        }

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-ANALYZERS', $analyserHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/datatables.js"></script>');

        $this->putBasedPage('analyzers', $finalHTML);
    }

    protected function getAnalyzersResultsCounts() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.implode('", "', $list).'"';

        $result = $this->sqlite->query(<<<SQL
        SELECT analyzer, count(*) AS issues, count(distinct file) AS files, severity AS severity FROM results
        WHERE analyzer IN ($list)
        GROUP BY analyzer
        HAVING Issues > 0
SQL
        );

        $return = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $analyzer = Analyzer::getInstance($row['analyzer'], null, $this->config);
            $row['label'] = $analyzer->getDescription()->getName();
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

    private function generateFiles() {
        $files = $this->getFilesResultsCounts();

        $baseHTML = $this->getBasedPage('files');
        $filesHTML = '';

        foreach ($files as $file) {
            $filesHTML.= "<tr>";
            $filesHTML.='<td>'.$file['file'].'</td>
                        <td>'.$file['loc'].'</td>
                        <td>'.$file['issues'].'</td>
                        <td>'.$file['analyzers'].'</td>';
            $filesHTML.= "</tr>";
        }

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-FILES', $filesHTML);
        $finalHTML = $this->injectBloc($finalHTML, 'BLOC-JS', '<script src="scripts/datatables.js"></script>');
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Files\' list');

        $this->putBasedPage('files', $finalHTML);
    }

    private function getFilesResultsCounts() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.implode('", "', $list).'"';

        $result = $this->sqlite->query(<<<SQL
SELECT file AS file, line AS loc, count(*) AS issues, count(distinct analyzer) AS analyzers FROM results
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

    public function getFilesCount($limit = null) {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.implode('", "', $list).'"';

        $query = "SELECT file, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY file
                    ORDER BY number DESC ";
        if ($limit !== null) {
            $query .= " LIMIT ".$limit;
        }
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = array('file'  => $row['file'],
                            'value' => $row['number']);
        }

        return $data;
    }

    private function getTopFile() {
        $data = $this->getFilesCount(self::TOPLIMIT);

        $html = '';
        foreach ($data as $value) {
            $html .= '<div class="clearfix">
                    <a href="#" title="'.$value['file'].'">
                      <div class="block-cell-name">'.$value['file'].'</div>
                      <div class="block-cell-issue text-center">'.$value['value'].'</div>
                    </a>
                  </div>';
        }
        $nb = 10 - count($data);
        for($i = 0; $i < $nb; ++$i) {
            $html .= '<div class="clearfix">
                      <div class="block-cell-name">&nbsp;</div>
                      <div class="block-cell-issue text-center">&nbsp;</div>
                  </div>';
        }

        return $html;
    }

    private function getFileOverview() {
        $data = $this->getFilesCount(self::LIMITGRAPHE);
        $xAxis        = array();
        $dataMajor    = array();
        $dataCritical = array();
        $dataNone     = array();
        $dataMinor    = array();
        $severities = $this->getSeveritiesNumberBy('file');
        foreach ($data as $value) {
            $xAxis[] = "'".$value['file']."'";
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

    private function getAnalyzersCount($limit) {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.implode('", "', $list).'"';

        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    WHERE analyzer in ($list)
                    GROUP BY analyzer
                    ORDER BY number DESC ";
        if ($limit) {
            $query .= " LIMIT ".$limit;
        }
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = array('analyzer' => $row['analyzer'],
                            'value'    => $row['number']);
        }

        return $data;
    }

    private function getTopAnalyzers() {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.implode('", "', $list).'"';

        $query = "SELECT analyzer, count(*) AS number
                    FROM results
                    WHERE analyzer IN ($list)
                    GROUP BY analyzer
                    ORDER BY number DESC
                    LIMIT ".self::TOPLIMIT;
        $result = $this->sqlite->query($query);
        $data = array();
        while ($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $analyzer = Analyzer::getInstance($row['analyzer'], null, $this->config);
            $data[] = array('label' => $analyzer->getDescription()->getName(),
                            'value' => $row['number']);
        }

        $html = '';
        foreach ($data as $value) {
            $html .= '<div class="clearfix">
                    <a href="#" title="'.$value['label'].'">
                      <div class="block-cell-name">'.$value['label'].'</div>
                      <div class="block-cell-issue text-center">'.$value['value'].'</div>
                    </a>
                  </div>';
        }

        return $html;
    }

    private function getSeveritiesNumberBy($type = 'file') {
        $list = Analyzer::getThemeAnalyzers($this->themesToShow);
        $list = '"'.implode('", "', $list).'"';

        $query = <<<SQL
SELECT $type, severity, count(*) AS count
    FROM results
    WHERE analyzer IN ($list)
    GROUP BY $type, severity
SQL;

        $stmt = $this->sqlite->query($query);

        $return = array();
        while ($row = $stmt->fetchArray(\SQLITE3_ASSOC) ) {
            if ( !isset($return[$row[$type]]) ) {
                $return[$row[$type]] = array($row['severity'] => $row['count']);
            } else {
                $return[$row[$type]][$row['severity']] = $row['count'];
            }
        }

        return $return;
    }

    private function getAnalyzerOverview() {
        $data = $this->getAnalyzersCount(self::LIMITGRAPHE);
        $xAxis        = array();
        $dataMajor    = array();
        $dataCritical = array();
        $dataNone     = array();
        $dataMinor    = array();

        $severities = $this->getSeveritiesNumberBy('analyzer');
        foreach ($data as $value) {
            $xAxis[] = "'".$value['analyzer']."'";
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
        $baseHTML = $this->getBasedPage('issues');

        $issues = implode(', ', $this->getIssuesFaceted('Security'));
        $blocjs = <<<JAVASCRIPT
        
  <script src="facetedsearch.js"></script>
  <script>
  "use strict";

    $(document).ready(function() {

      var data_items = [$issues];
      var item_template =  
        '<tr>' +
          '<td width="20%"><%= obj.analyzer %></td>' +
          '<td width="20%"><%= obj.file + ":" + obj.line %></td>' +
          '<td width="18%"><%= obj.code %></td>' + 
          '<td width="2%"><%= obj.code_detail %></td>' +
          '<td width="7%" align="center"><%= obj.severity %></td>' +
          '<td width="7%" align="center"><%= obj.complexity %></td>' +
          '<td width="16%"><%= obj.recipe %></td>' +
        '</tr>' +
        '<tr class="fullcode">' +
          '<td colspan="7" width="100%"><div class="analyzer_help"><%= obj.analyzer_help %></div><pre><code><%= obj.code_plus %></code><div class="text-right"><a target="_BLANK" href="codes.html?file=<%= obj.link_file %>" class="btn btn-info">View File</a></div></pre></td>' +
        '</tr>';
      var settings = { 
        items           : data_items,
        facets          : { 
          'analyzer'  : 'Analyzer',
          'file'      : 'File',
          'severity'  : 'Severity',
          'complexity': 'Complexity',
          'receipt'   : 'Receipt'
        },
        facetContainer     : '<div class="facetsearch btn-group" id=<%= id %> ></div>',
        facetTitleTemplate : '<button class="facettitle multiselect dropdown-toggle btn btn-default" data-toggle="dropdown" title="None selected"><span class="multiselect-selected-text"><%= title %></span><b class="caret"></b></button>',
        facetListContainer : '<ul class="facetlist multiselect-container dropdown-menu"></ul>',
        listItemTemplate   : '<li class=facetitem id="<%= id %>" data-analyzer="<%= data_analyzer %>" data-file="<%= data_file %>"><span class="check"></span><%= name %><span class=facetitemcount>(<%= count %>)</span></li>',
        bottomContainer    : '<div class=bottomline></div>',  
        resultSelector   : '#results',
        facetSelector    : '#facets',
        resultTemplate   : item_template,
        paginationCount  : 50
      }   
      $.facetelize(settings);
      
      var analyzerParam = window.location.search.split('analyzer=')[1];
      var fileParam = window.location.search.split('file=')[1];
      if(analyzerParam !== undefined) {
        $('#analyzer .facetlist').find("[data-analyzer='" + analyzerParam + "']").click();
      }
      if(fileParam !== undefined) {
        $('#file .facetlist').find("[data-file='" + fileParam + "']").click();
      }
    });
  </script>
JAVASCRIPT;

        $finalHTML = $this->injectBloc($baseHTML, 'BLOC-JS', $blocjs);
        $finalHTML = $this->injectBloc($finalHTML, 'TITLE', 'Issues\' list');
        $this->putBasedPage('issues', $finalHTML);
    }

    public function getIssuesFaceted($theme) {
        $list = Analyzer::getThemeAnalyzers($theme);
        $list = '"'.implode('", "', $list).'"';

        $sqlQuery = <<<SQL
            SELECT fullcode, file, line, analyzer
                FROM results
                WHERE analyzer IN ($list)

SQL;
        $result = $this->sqlite->query($sqlQuery);

        $items = array();
        while($row = $result->fetchArray(\SQLITE3_ASSOC)) {
            $item = array();
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$row['analyzer'].'.ini');
            $item['analyzer'] =  $ini['name'];
            $item['analyzer_md5'] = md5($ini['name']);
            $item['file' ] =  $row['file'];
            $item['file_md5' ] =  md5($row['file']);
            $item['code' ] = PHPSyntax($row['fullcode']);
            $item['code_detail'] = "<i class=\"fa fa-plus \"></i>";
            $item['code_plus'] = PHPSyntax($row['fullcode']);
            $item['link_file'] = $row['file'];
            $item['line' ] =  $row['line'];
            $item['severity'] = "<i class=\"fa fa-warning ".$this->severities[$row['analyzer']]."\"></i>";
            $item['complexity'] = "<i class=\"fa fa-cog ".$this->timesToFix[$row['analyzer']]."\"></i>";
            $item['recipe' ] =  implode(', ', $this->themesForAnalyzer[$row['analyzer']]);
            $lines = explode("\n", $ini['description']);
            $item['analyzer_help' ] = $lines[0];

            $items[] = json_encode($item);
            $this->count();
        }

        return $items;
    }

    private function getClassByType($type)
    {
        if ($type == 'Critical' || $type == 'Long') {
            $class = 'text-orange';
        } elseif ($type == 'Major' || $type == 'Slow') {
            $class = 'text-red';
        } elseif ($type == 'Minor' || $type == 'Quick') {
            $class = 'text-yellow';
        }  elseif ($type == 'Note' || $type == 'Instant') {
            $class = 'text-blue';
        } else {
            $class = 'text-gray';
        }

        return $class;
    }

    private function generateSettings() {
        $info = array(array('Code name', $this->config->project_name));
        if (!empty($this->config->project_description)) {
            $info[] = array('Code description', $this->config->project_description);
        }
        if (!empty($this->config->project_packagist)) {
            $info[] = array('Packagist', '<a href="https://packagist.org/packages/'.$this->config->project_packagist.'">'.$this->config->project_packagist.'</a>');
        }
        if (!empty($this->config->project_url)) {
            $info[] = array('Home page', '<a href="'.$this->config->project_url.'">'.$this->config->project_url.'</a>');
        }
        if (file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/code/.git/config')) {
            $gitConfig = file_get_contents($this->config->projects_root.'/projects/'.$this->config->project.'/code/.git/config');
            preg_match('#url = (\S+)\s#is', $gitConfig, $r);
            $info[] = array('Git URL', $r[1]);

            $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$this->config->project.'/code/; git branch');
            $info[] = array('Git branch', trim($res));

            $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$this->config->project.'/code/; git rev-parse HEAD');
            $info[] = array('Git commit', trim($res));
        } else {
            $info[] = array('Repository URL', 'Downloaded archive');
        }

        $info[] = array('Number of PHP files', $this->datastore->getHash('files'));
        $info[] = array('Number of lines of code', $this->datastore->getHash('loc'));
        $info[] = array('Number of lines of code with comments', $this->datastore->getHash('locTotal'));

        $info[] = array('Report production date', date('r', strtotime('now')));

        $php = new Phpexec($this->config->phpversion, $this->config->{'php'.str_replace('.', '', $this->config->phpversion)});
        $info[] = array('PHP used', $php->getActualVersion().' (version '.$this->config->phpversion.' configured)');
        $info[] = array('Ignored files/folders', implode(', ', $this->config->ignore_dirs));

        $info[] = array('Exakat version', Exakat::VERSION.' ( Build '.Exakat::BUILD.') ');

        $settings = '';
        foreach($info as $i) {
            $settings .= "<tr><td>$i[0]</td><td>$i[1]</td></tr>";
        }

        $html = $this->getBasedPage('used_settings');
        $html = $this->injectBloc($html, 'SETTINGS', $settings);
        $html = $this->injectBloc($html, 'TITLE', 'Analyzer settings\' list');

        $this->putBasedPage('used_settings', $html);
    }

    private function generateProcFiles() {
        $files = '';
        $fileList = $this->datastore->getCol('files', 'file');
        foreach($fileList as $file) {
            $files .= "<tr><td>$file</td></tr>\n";
        }

        $nonFiles = '';
        $ignoredFiles = $this->datastore->getRow('ignoredFiles');
        foreach($ignoredFiles as $row) {
            if (empty($row['file'])) { continue; }

            $nonFiles .= "<tr><td>{$row['file']}</td><td>{$row['reason']}</td></tr>\n";
        }

        $html = $this->getBasedPage('proc_files');
        $html = $this->injectBloc($html, 'FILES', $files);
        $html = $this->injectBloc($html, 'NON-FILES', $nonFiles);
        $html = $this->injectBloc($html, 'TITLE', 'Processed Files\' list');

        $this->putBasedPage('proc_files', $html);
    }

    private function generateAnalyzersList() {
        $analyzers = '';

        foreach(Analyzer::getThemeAnalyzers($this->themesToShow) as $analyzer) {
            $analyzer = Analyzer::getInstance($analyzer, null, $this->config);
            $description = $analyzer->getDescription();

            $analyzers .= "<tr><td>".$description->getName()."</td></tr>\n";
        }

        $html = $this->getBasedPage('proc_analyzers');
        $html = $this->injectBloc($html, 'ANALYZERS', $analyzers);
        $html = $this->injectBloc($html, 'TITLE', 'Processed Analyzers\' list');

        $this->putBasedPage('proc_analyzers', $html);
    }

    private function generateExternalLib() {
        $externallibraries = json_decode(file_get_contents($this->config->dir_root.'/data/externallibraries.json'));

        $libraries = '';
        $externallibrariesList = $this->datastore->getRow('externallibraries');

        foreach($externallibrariesList as $row) {
            $url = $externallibraries->{strtolower($row['library'])}->homepage;
            $name = $externallibraries->{strtolower($row['library'])}->name;
            if (empty($url)) {
                $homepage = '';
            } else {
                $homepage = "<a href=\"".$url."\">".$row['library']."</a>";
            }
            $libraries .= "<tr><td>$name</td><td>$row[file]</td><td>$homepage</td></tr>\n";
        }

        $html = $this->getBasedPage('ext_lib');
        $html = $this->injectBloc($html, 'LIBRARIES', $libraries);
        $html = $this->injectBloc($html, 'TITLE', 'External Libraries\' list');

        $this->putBasedPage('ext_lib', $html);
    }



    protected function generateAnalyzerSettings() {
        $settings = '';

        $info = array(array('Code name', $this->config->project_name));
        if (!empty($this->config->project_description)) {
            $info[] = array('Code description', $this->config->project_description);
        }
        if (!empty($this->config->project_packagist)) {
            $info[] = array('Packagist', '<a href="https://packagist.org/packages/'.$this->config->project_packagist.'">'.$this->config->project_packagist.'</a>');
        }
        if (!empty($this->config->project_url)) {
            $info[] = array('Home page', '<a href="'.$this->config->project_url.'">'.$this->config->project_url.'</a>');
        }
        if (file_exists($this->config->projects_root.'/projects/'.$this->config->project.'/code/.git/config')) {
            $gitConfig = file_get_contents($this->config->projects_root.'/projects/'.$this->config->project.'/code/.git/config');
            preg_match('#url = (\S+)\s#is', $gitConfig, $r);
            $info[] = array('Git URL', $r[1]);

            $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$this->config->project.'/code/; git branch');
            $info[] = array('Git branch', trim($res));

            $res = shell_exec('cd '.$this->config->projects_root.'/projects/'.$this->config->project.'/code/; git rev-parse HEAD');
            $info[] = array('Git commit', trim($res));
        } else {
            $info[] = array('Repository URL', 'Downloaded archive');
        }

        $info[] = array('Number of PHP files', $this->datastore->getHash('files'));
        $info[] = array('Number of lines of code', $this->datastore->getHash('loc'));
        $info[] = array('Number of lines of code with comments', $this->datastore->getHash('locTotal'));

        $info[] = array('Analysis execution date', date('r', $this->datastore->getHash('audit_end')));
        $info[] = array('Analysis runtime', duration($this->datastore->getHash('audit_end') - $this->datastore->getHash('audit_start')));
        $info[] = array('Report production date', date('r', strtotime('now')));

        $php = new Phpexec($this->config->phpversion, $this->config->{'php'.str_replace('.', '', $this->config->phpversion)});
        $info[] = array('PHP used', $this->config->phpversion.' ('.$php->getActualVersion().')');

        $info[] = array('Exakat version', Exakat::VERSION.' ( Build '.Exakat::BUILD.') ');

        foreach($info as &$row) {
            $row = '<tr><td>'.implode('</td><td>', $row).'</td></tr>';
        }
        unset($row);

        $settings = implode('', $info);

        $html = $this->getBasedPage('annex_settings');
        $html = $this->injectBloc($html, 'SETTINGS', $settings);
        $this->putBasedPage('annex_settings', $html);
    }

    private function generateExternalServices() {
        $externalServices = '';

        $res = $this->datastore->getRow('configFiles');
        foreach($res as $row) {
            if (empty($row['homepage'])) {
                $link = '';
            } else {
                $link = "<a href=\"".$row['homepage']."\">".$row['homepage']."&nbsp;<i class=\"fa fa-sign-out\"></i></a>";
            }

            $externalServices .= "<tr><td>$row[name]</td><td>$row[file]</td><td>$link</td></tr>\n";
        }

        $html = $this->getBasedPage('external_services');
        $html = $this->injectBloc($html, 'EXTERNAL_SERVICES', $externalServices);
        $this->putBasedPage('external_services', $html);
    }

    private function generateDirectiveList() {
        // @todo automate this : Each string must be found in Report/Content/Directives/*.php and vice-versa
        $directives = array('standard', 'bcmath', 'date', 'file',
                            'fileupload', 'mail', 'ob', 'env',
                            // standard extensions
                            'apc', 'amqp', 'apache', 'assertion', 'curl', 'dba',
                            'filter', 'image', 'intl', 'ldap',
                            'mbstring',
                            'opcache', 'openssl', 'pcre', 'pdo', 'pgsql',
                            'session', 'sqlite', 'sqlite3',
                            // pecl extensions
                            'com', 'eaccelerator',
                            'geoip', 'ibase',
                            'imagick', 'mailparse', 'mongo',
                            'trader', 'wincache', 'xcache'
                             );

        $directiveList = '';
        $res = $this->sqlite->query(<<<SQL
SELECT analyzer FROM resultsCounts 
    WHERE ( analyzer LIKE "Extensions/Ext%" OR 
            analyzer IN ("Structures/FileUploadUsage", "Php/UsesEnv"))
        AND count > 0
SQL
        );
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if ($row['analyzer'] == 'Structures/FileUploadUsage') {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>File Upload</td></tr>\n";
                $data['File Upload'] = (array) json_decode(file_get_contents($this->config->dir_root.'/data/directives/fileupload.json'));
            } elseif ($row['analyzer'] == 'Php/UsesEnv') {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>Environnement</td></tr>\n";
                $data['Environnement'] = (array) json_decode(file_get_contents($this->config->dir_root.'/data/directives/env.json'));
            } elseif ($row['analyzer'] == 'Php/ErrorLogUsage') {
                $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>Error Log</td></tr>\n";
                $data['Errorlog'] = (array) json_decode(file_get_contents($this->config->dir_root.'/data/directives/errorlog.json'));
            } else {
                $ext = substr($row['analyzer'], 14);
                if (in_array($ext, $directives)) {
                    $data = json_decode(file_get_contents($this->config->dir_root.'/data/directives/'.$ext.'.json'));
                    $directiveList .= "<tr><td colspan=3 bgcolor=#AAA>$ext</td></tr>\n";
                    foreach($data as $row) {
                        $directiveList .= "<tr><td>$row->name</td><td>$row->suggested</td><td>$row->documentation</td></tr>\n";
                    }
                }
            }
        }

        $html = $this->getBasedPage('directive_list');
        $html = $this->injectBloc($html, 'DIRECTIVE_LIST', $directiveList);
        $this->putBasedPage('directive_list', $html);
    }

    private function generateCompilations() {
        $compilations = '';

        $total = $this->sqlite->querySingle('SELECT value FROM hash WHERE key = "files"');
        $info = array();
        foreach($this->config->other_php_versions as $suffix) {
            $res = $this->sqlite->querySingle('SELECT name FROM sqlite_master WHERE type="table" AND name="compilation'.$suffix.'"');
            if (!$res) {
                continue; // Table was not created
            }

            $res = $this->sqlite->query('SELECT file FROM compilation'.$suffix);
            $files = array();
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $files[] = $row['file'];
            }
            $version = $suffix[0].'.'.substr($suffix, 1);
            if (empty($files)) {
                $files       = 'No compilation error found.';
                $errors      = 'N/A';
                $total_error = 'N/A';
            } else {
                $res = $this->sqlite->query('SELECT error FROM compilation'.$suffix);
                $readErrors = array();
                while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                    $readErrors[] = $row['error'];
                }
                $errors      = array_count_values($readErrors);
                $errors      = array_keys($errors);
                $errors      = array_keys(array_count_values($errors));
                $errors       = '<ul><li>'.implode("</li>\n<li>", $errors).'</li></ul>';

                $total_error = count($files).' ('.number_format(count($files) / $total * 100, 0).'%)';
                $files       = array_keys(array_count_values($files));
                $files       = '<ul><li>'.implode("</li>\n<li>", $files).'</li></ul>';
            }

            $compilations .= "<tr><td>$version</td><td>$total</td><td>$total_error</td><td>$files</td><td>$errors</td></tr>\n";
        }

        $html = $this->getBasedPage('compatibility_compilations');
        $html = $this->injectBloc($html, 'COMPILATIONS', $compilations);
        $html = $this->injectBloc($html, 'TITLE', 'Compilations overview');
        $this->putBasedPage('compatibility_compilations', $html);
    }

    private function generateCompatibility($version) {
        $compatibility = '';

        $list = Analyzer::getThemeAnalyzers('CompatibilityPHP'.$version);

        $res = $this->sqlite->query('SELECT analyzer, counts FROM analyzed');
        $counts = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[$row['analyzer']] = $row['counts'];
        }

        foreach($list as $l) {
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$l.'.ini');
            if (isset($counts[$l])) {
                $result = (int) $counts[$l];
            } else {
                $result = -1;
            }
            $result = $this->Compatibility($result);
            $name = $ini['name'];
            $link = '<a href="analyzers_doc.html#'.md5($name).'" alt="Documentation for $name"><i class="fa fa-book"></i></a>';
            $compatibility .= "<tr><td>$name $link</td><td>$result</td></tr>\n";
        }

        $description = <<<HTML
<i class="fa fa-check-square-o"></i> : Nothing found for this analysis, proceed with caution; <i class="fa fa-warning red"></i> : some issues found, check this; <i class="fa fa-ban"></i> : Can't test this, PHP version incompatible; <i class="fa fa-cogs"></i> : Can't test this, PHP configuration incompatible; 
HTML;

        $html = $this->getBasedPage('compatibility');
        $html = $this->injectBloc($html, 'COMPATIBILITY', $compatibility);
        $html = $this->injectBloc($html, 'TITLE', 'Compatibility PHP '.$version[0].'.'.$version[1]);
        $html = $this->injectBloc($html, 'DESCRIPTION', $description);
        $this->putBasedPage('compatibility_php'.$version, $html);
    }

    private function generateDynamicCode() {
        $dynamicCode = '';

        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Structures/DynamicCode"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $dynamicCode .= '<tr><td>'.PHPSyntax($row['fullcode'])."</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
        }

        $html = $this->getBasedPage('dynamic_code');
        $html = $this->injectBloc($html, 'DYNAMIC_CODE', $dynamicCode);
        $this->putBasedPage('dynamic_code', $html);
    }

    private function generateGlobals() {
        $theGlobals = '';
        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Structures/GlobalInGlobal"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $theGlobals .= '<tr><td>'.PHPSyntax($row['fullcode'])."</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
        }

        $html = $this->getBasedPage('globals');
        $html = $this->injectBloc($html, 'GLOBALS', $theGlobals);
        $this->putBasedPage('globals', $html);
    }

    private function generateInventories() {
        $definitions = array(
            'constants'  => array('description' => 'List of all defined constants in the code.',
                                  'analyzer'    => 'Constants/Constantnames'),
            'classes'    => array('description' => 'List of all defined classes in the code.',
                                  'analyzer'    => 'Classes/Classnames'),
            'interfaces' => array('description' => 'List of all defined interfaces in the code.',
                                  'analyzer'    => 'Interfaces/Interfacenames'),
            'traits'     => array('description' => 'List of all defined traits in the code.',
                                  'analyzer'    => 'Traits/Traitnames'),
            'functions'  => array('description' => 'List of all defined functions in the code.',
                                  'analyzer'    => 'Functions/Functionnames'),
            'namespaces' => array('description' => 'List of all defined namespaces in the code.',
                                  'analyzer'    => 'Namespaces/Namespacesnames'),
            'exceptions' => array('description' => 'List of all defined exceptions.',
                                  'analyzer'    => 'Exceptions/DefinedExceptions'),
        );
        foreach($this->inventories as $fileName => $theTitle) {
            $theDescription = $definitions[$fileName]['description'];
            $theAnalyzer    = $definitions[$fileName]['analyzer'];

            $theTable = '';
            $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="'.$theAnalyzer.'"');
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $theTable .= '<tr><td>'.PHPSyntax($row['fullcode'])."</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
            }

            $html = $this->getBasedPage('inventories');
            $html = $this->injectBloc($html, 'TITLE', $theTitle);
            $html = $this->injectBloc($html, 'DESCRIPTION', $theDescription);
            $html = $this->injectBloc($html, 'TABLE', $theTable);
            $this->putBasedPage('inventories_'.$fileName, $html);
        }
    }

    private function generateAlteredDirectives() {
        $alteredDirectives = '';
        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Php/DirectivesUsage"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $alteredDirectives .= '<tr><td>'.PHPSyntax($row['fullcode'])."</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
        }

        $html = $this->getBasedPage('altered_directives');
        $html = $this->injectBloc($html, 'ALTERED_DIRECTIVES', $alteredDirectives);
        $this->putBasedPage('altered_directives', $html);
    }

    private function generateStats() {
        $extensions = array(
                    'Summary' => array(
                            'Namespaces'     => 'Namespace',
                            'Classes'        => 'Class',
                            'Interfaces'     => 'Interface',
                            'Trait'          => 'Trait',
                            'Function'       => 'Functions/RealFunctions',
                            'Variables'      => 'Variables/RealVariables',
                            'Constants'      => 'Constants/Constantnames',
                     ),
                    'Classes' => array(
                            'Classes'           => 'Class',
                            'Class constants'   => 'Classes/ConstantDefinition',
                            'Properties'        => 'Classes/NormalProperties',
                            'Static properties' => 'Classes/StaticProperties',
                            'Methods'           => 'Classes/NormalMethods',
                            'Static methods'    => 'Classes/StaticMethods',
                            // Spot Abstract methods
                            // Spot Final Methods
                     ),
                    'Structures' => array(
                            'Ifthen'              => 'Ifthen',
                            'Else'                => 'Structures/ElseUsage',
                            'Switch'              => 'Switch',
                            'Case'                => 'Case',
                            'Default'             => 'Default',
                            'For'                 => 'For',
                            'Foreach'             => 'Foreach',
                            'While'               => 'While',
                            'Do..while'           => 'Dowhile',

                            'New'                 => 'New',
                            'Clone'               => 'Clone',
                            'Class constant call' => 'Staticconstant',
                            'Method call'         => 'Methodcall',
                            'Static method call'  => 'Staticmethodcall',
                            'Properties usage'    => 'Property',
                            'Static property'     => 'Staticproperty',

                            'Throw'               => 'Throw',
                            'Try'                 => 'Try',
                            'Catch'               => 'Catch',
                            'Finally'             => 'Finally',

                            'Yield'               => 'Yield',
                            'Yield From'          => 'Yieldfrom',

                            '?  :'                => 'Ternary',
                            '?: '                 => 'Php/Coalesce',
                            '??'                  => 'Php/NullCoalesce',

                            'Variables constants' => 'Constants/VariableConstants',
                            'Variables variables' => 'Variables/VariableVariable',
                            'Variables functions' => 'Functions/Dynamiccall',
                            'Variables classes'   => 'Classes/VariableClasses',
                    ),
                );

        $stats = '';
        foreach($extensions as $section => $hash) {
            $stats .= "<tr><td colspan=2 bgcolor=#BBB>$section</td></tr>\n";

            foreach($hash as $name => $ext) {
                if (strpos($ext, '/') === false) {
                    $res = $this->sqlite->query('SELECT count FROM atomsCounts WHERE atom="'.$ext.'"');
                    $d = $res->fetchArray(\SQLITE3_ASSOC);
                    $d = (int) $d['count'];
                } else {
                    $res = $this->sqlite->query('SELECT count FROM resultsCounts WHERE analyzer="'.$ext.'"');
                    $d = $res->fetchArray(\SQLITE3_ASSOC);
                    $d = (int) $d['count'];
                }
                $res = $d === -2 ? 'N/A' : $d;
                $stats .= "<tr><td>$name</td><td>$res</td></tr>\n";
            }
        }

        $html = $this->getBasedPage('stats');
        $html = $this->injectBloc($html, 'STATS', $stats);
        $this->putBasedPage('stats', $html);
    }

    private function generateCodes() {
        mkdir($this->tmpName.'/datas/sources/', 0755);

        $filesList = $this->datastore->getRow('files');
        $files = '';
        foreach($filesList as $row) {
            $id = str_replace('/', '_', $row['file']);

            $subdirs = explode('/', dirname($row['file']));
            $dir = $this->tmpName.'/datas/sources';
            foreach($subdirs as $subdir) {
                $dir .= '/'.$subdir;
                if (!file_exists($dir)) {
                    mkdir($dir, 0755);
                }
            }

            $source = show_source(dirname($this->tmpName).'/code/'.$row['file'], true);
            $files .= '<li><a href="#" id="'.$id.'" class="menuitem">'.htmlentities($row['file'], ENT_COMPAT | ENT_HTML401 , 'UTF-8')."</a></li>\n";
            file_put_contents($this->tmpName.'/datas/sources/'.$row['file'], substr($source, 6, -8));
        }

        $blocjs = <<<JAVASCRIPT
  <script src="facetedsearch.js"></script>


  <script>
  "use strict";

  $('.menuitem').click(function(event){
    $('#results').load("sources/" + event.target.text);
    $('#filename').html(event.target.text + '  <span class="caret"></span>');
  });

  var fileParam = window.location.search.split('file=')[1];
  if(fileParam !== undefined) {
    $('#results').load("sources/" + fileParam);
    $('#filename').html(fileParam + '  <span class="caret"></span>');
  }
  

  </script>
JAVASCRIPT;
        $html = $this->getBasedPage('codes');
        $html = $this->injectBloc($html, 'BLOC-JS', $blocjs);
        $html = $this->injectBloc($html, 'FILES', $files);

        $this->putBasedPage('codes', $html);
    }

    private function generateAppinfo() {
        $extensions = $this->components;

        // collecting information for Extensions
        $themed = Analyzer::getThemeAnalyzers('ZendFramework');
        $res = $this->sqlite->query('SELECT analyzer, count FROM resultsCounts WHERE analyzer IN ("'.implode('", "', $themed).'")');
        $sources = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $sources[$row['analyzer']] = $row['count'];
        }
        $data = array();

        foreach($extensions as $section => $hash) {
            $data[$section] = array();

            foreach($hash as $name => $ext) {
                if (!isset($sources[$ext])) {
                    $data[$section][$name] = self::NOT_RUN;
                    continue;
                }
                if (!in_array($ext, $themed)) {
                    $data[$section][$name] = self::NOT_RUN;
                    continue;
                }

                // incompatible
                if ($sources[$ext] == Analyzer::CONFIGURATION_INCOMPATIBLE) {
                    $data[$section][$name] = self::INCOMPATIBLE;
                    continue ;
                }

                if ($sources[$ext] == Analyzer::VERSION_INCOMPATIBLE) {
                    $data[$section][$name] = self::INCOMPATIBLE;
                    continue ;
                }

                $data[$section][$name] = $sources[$ext] > 0 ? self::YES : self::NO;
            }

            if ($section == 'Extensions') {
                $list = $data[$section];
                uksort($data[$section], function ($ka, $kb) use ($list) {
                    if ($list[$ka] == $list[$kb]) {
                        if ($ka > $kb)  { return  1; }
                        if ($ka == $kb) { return  0; }
                        if ($ka > $kb)  { return -1; }
                    } else {
                        return $list[$ka] == self::YES ? -1 : 1;
                    }
                });
            }
        }
        // collecting information for Composer
        if (isset($sources['Composer/PackagesNames'])) {
            $data['Composer Packages'] = array();
            $res = $this->sqlite->query('SELECT fullcode FROM results WHERE analyzer = "Composer/PackagesNames"');
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $data['Composer Packages'][] = PHPSyntax($row['fullcode']);
            }
        } else {
            unset($data['Composer Packages']);
        }

        $list = array();
        foreach($data as $section => $points) {
            $listPoint = array();
            foreach($points as $point => $status) {
                $listPoint[] = '<li>'.$this->makeIcon($status).'&nbsp;'.$point.'</li>';
            }

            $listPoint = implode("\n", $listPoint);
            $list[] = <<<HTML
        <ul class="sidebar-menu">
          <li class="treeview">
            <a href="#"><i class="fa fa-certificate"></i> <span>$section</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                $listPoint
            </ul>
          </li>
        </ul>
HTML;
        }

        $list = implode("\n", $list);
        $list = <<<HTML
        <div class="sidebar">
$list
        </div>
HTML;

        $html = $this->getBasedPage('appinfo');
        $html = $this->injectBloc($html, 'APPINFO', $list);
        $this->putBasedPage('appinfo', $html);
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

    private function Bugfixes_cve($cve) {
        if (!empty($cve)) {
            if (strpos($cve, ', ') !== false) {
                $cves = explode(', ', $cve);
                $cveHtml = array();
                foreach($cves as $cve) {
                    $cveHtml[] = '<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name='.$cve.'">'.$cve.'</a>';
                }
                $cveHtml = implode(',<br />', $cveHtml);
            } else {
                $cveHtml = '<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name='.$cve.'">'.$cve.'</a>';
            }
        } else {
            $cveHtml = '-';
        }

        return $cveHtml;
    }

    private function Compatibility($count) {
        if ($count == Analyzer::VERSION_INCOMPATIBLE) {
            return '<i class="fa fa-ban"></i>';
        } elseif ($count == Analyzer::CONFIGURATION_INCOMPATIBLE) {
            return '<i class="fa fa-cogs"></i>';
        } elseif ($count === 0) {
            return '<i class="fa fa-check-square-o"></i>';
        } else {
            return '<i class="fa fa-warning red"></i>&nbsp;'.$count.' warnings';
        }
    }
    
    private function generateUnusedComponents() {
        $composerJson = file_get_contents( $this->config->projects_root.'/projects/'.$this->config->project.'/code/composer.json');
        $composer = json_decode($composerJson);
        if ($composer === null) {
            die('No composer in ');
        }
        $require = $composer->require;

        $themed = $this->components['Components'];
        $res = $this->sqlite->query('SELECT analyzer, count FROM resultsCounts WHERE analyzer IN ("'.implode('", "', $themed).'") ORDER BY analyzer');
        $sources = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $sources[$row['analyzer']] = $row['count'];
        }

        $table = '<table class="table table-striped">
        						<tr></tr>
        						<tr><th>Component</th><th>composer.json</th><th>used</th></tr>';
        						
        foreach($sources as $s => $c) {
            $composerName = preg_replace('#zendf/zf3(.*?)#', 'zendframework/zend-$1', strtolower($s));
            
            // if 
            if (isset($require->{'zendframework/zendframework'})) {
                $inComposer = $require->{'zendframework/zendframework'};
            } else {
                $inComposer = isset($require->{$composerName}) ? $require->{$composerName} : 'N/A';
            }
            $table .= "						<tr><td>$s</td><td>".$inComposer."</td><td>".($c === 0 ? '<i class="fa fa-square-o"></i>' : '<i class="fa fa-check-square-o"></i>')."</td></tr>\n";
        }
        $table .= '        					</table>';

        $html = $this->getBasedPage('empty');
        $html = $this->injectBloc($html, 'TITLE', 'Components');
        $html = $this->injectBloc($html, 'DESCRIPTION', '<p>List of Zend Framework components and their usage.</p>');
        $html = $this->injectBloc($html, 'CONTENT', $table);
        $this->putBasedPage('unusedComponents', $html);
    }

    private function generateErrorMessages() {
        $errorMessages = '';

        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Structures/ErrorMessages"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $errorMessages .= '<tr><td>'.PHPsyntax($row['fullcode'])."</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
        }

        $html = $this->getBasedPage('error_messages');
        $html = $this->injectBloc($html, 'ERROR_MESSAGES', $errorMessages);
        $this->putBasedPage('error_messages', $html);
    }

    private function generateExceptionInventory() {
        $exceptionInventory = '';

        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="ZendF/ThrownExceptions"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $exceptionInventory .= '<tr><td>'.PHPsyntax($row['fullcode'])."</td><td>$row[file]</td><td>$row[line]</td></tr>\n";
        }

        $table = '<table class="table table-striped">
        						<tr></tr>
        						<tr><th>Exception</th><th>File</th><th>line</th></tr>'
        						.$exceptionInventory.
        						'        					</table>';
        $html = $this->getBasedPage('empty');
        $html = $this->injectBloc($html, 'CONTENT', $table);
        $this->putBasedPage('thrown_exceptions', $html);
    }

    protected function makeAuditDate(&$finalHTML) {
        $audit_date = 'Audit date : '.date('d-m-Y h:i:s', time());
        $audit_name = $this->datastore->getHash('audit_name');
        if (!empty($audit_name)) {
            $audit_date .= ' - &quot;'.$audit_name.'&quot;';
        }
        $finalHTML = $this->injectBloc($finalHTML, 'AUDIT_DATE', $audit_date);
    }

}

?>