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


namespace Reports;

class Devoops {
    const FOLDER_PRIVILEGES = 0755;
    
    private $dump      = null; // Dump.sqlite
    private $datastore = null; // Datastore.sqlite
    
    public function generateFileReport($report) {
        $out = new XMLWriter;
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
//                    if (PHP_CODESNIFFER_ENCODING !== 'utf-8') {
//                        $error['message'] = iconv(PHP_CODESNIFFER_ENCODING, 'utf-8', $error['message']);
//                    }

                    $out->startElement($error['type']);
                    $out->writeAttribute('line', $line);
                    $out->writeAttribute('column', $column);
                    $out->writeAttribute('source', $error['source']);
                    $out->writeAttribute('severity', $error['severity']);
                    $out->writeAttribute('fixable', (int) $error['fixable']);
                    $out->text($error['message']);
                    $out->endElement();
                    ++$this->count;
                }
            }
        }//end foreach

        $out->endElement();
        $this->cachedData .= $out->flush();

        return true;

    }//end generateFileReport()

    public function generate($folder, $name) {
        shell_exec('rm -rf '.$folder.'/'.$name);

        $config = \Config::factory();
        mkdir($folder.'/'.$name, Devoops::FOLDER_PRIVILEGES);
        mkdir($folder.'/'.$name.'/ajax', Devoops::FOLDER_PRIVILEGES);

        $this->copyDir($config->dir_root.'/media/devoops/css', $folder.'/'.$name.'/css');
        $this->copyDir($config->dir_root.'/media/devoops/img', $folder.'/'.$name.'/img');
        $this->copyDir($config->dir_root.'/media/devoops/js', $folder.'/'.$name.'/js');
        $this->copyDir($config->dir_root.'/media/devoops/plugins', $folder.'/'.$name.'/plugins');
        
        $this->dump      = new \sqlite3($folder.'/dump.sqlite');
        $this->datastore = new \sqlite3($folder.'/datastore.sqlite');
        
        // Compatibility
        $compatibility = array('Compilation' => 'Compilation');
        foreach($config->other_php_versions as $code) {
            if ($code == 52) { continue; }

            $version = $code[0].'.'.substr($code, 1);
            $compatibility['Compatibility '.$version] = 'Compatiliblity'.$code;
        }

        // Analyze
        $analyze = array();
        $res = $this->dump->query('SELECT * FROM resultsCounts WHERE count > 0');
        while($row = $res->fetchArray()) {
            $analyzer = \Analyzer\Analyzer::getInstance($row['analyzer']);
            
            if (empty($analyzer->getDescription()->getName())) {
                print_r($row);
            }
            $analyze[$analyzer->getDescription()->getName()] = 'Compatiliblity'.$code;
        }
        ksort($analyze);
        $analyze = array_merge(array('Results Counts' => 'AnalyzeResultCounts'), $analyze);

        // Files
        $files = array();
        $res = $this->dump->query('SELECT DISTINCT file FROM results ORDER BY file');
        while($row = $res->fetchArray()) {
            $files[$row['file']] = 'Files';
        }
        $files = array_merge(array('Files Counts' => 'FileResultCounts'), $files);
        
        $summary = array(
            'Report presentation' => array('Audit configuration' => 'AuditConfiguration'),
            'Analysis'            => array('Code Smells'         => 'DashboardCodesmell',
                                           'Dead Code'           => 'DeadCode',
                                           'Security'            => 'Security',
                                           'Performances'        => 'Performances'),
            'Compatibility'       => $compatibility,
            'By analyze'          => $analyze,
            'By file'             => $files,
            'Application'         => array('Appinfo()'              => 'Appinfo',
                                           'PHP Directives'         => 'Directives',
                                           'Altered Directives'     => 'AlteredDirectives',
                                           'Dynamic Code'           => 'DynamicCode',
                                           'Stats'                  => 'Stats',
                                           'Global Variables List'  => 'GlobalVariablesList',
                                           'External Config Files'  => 'ExternalConfigFiles',
                                           'Error Messages'         => 'ErrorMessages'),
            'Annexes'             => array('Documentation'          => 'Documentation',
                                           'Processed Files'        => 'ProcessedFiles',
                                           'Non-processed Files'    => 'NonProcessedFiles',
                                           'External Libraries'     => 'ExternalLibraries',
                                           'Analyzers'              => 'Analyzers',
                                           'About This Report'      => 'AboutThisReport'),
        );
        
        $summaryHtml = $this->makeSummary($summary);
        
        $faviconHtml = '';
        if (file_exists($config->dir_root.'/projects/'.$config->project.'/code/favicon.ico')) {
            // Should be checked and reported
            copy($config->dir_root.'/projects/'.$config->project.'/code/favicon.ico', $dir.'/img/'.$this->projectName.'.ico');
            
            $faviconHtml = <<<HTML
<img src="img/{$this->projectName}.ico" class="img-circle" alt="{$this->projectName} logo" />
HTML;

            if (!empty($config->project_url)) {
                $faviconHtml = "<a href=\"{$config->project_url}\" class=\"avatar\">$faviconHtml</a>";
            }

            $faviconHtml = <<<HTML
				<div class="avatar">
					$faviconHtml
				</div>
HTML;
        } 

        $html = file_get_contents('./media/devoops/index.exakat.html');
        $html = str_replace('<menu>', $summaryHtml, $html);

        $html = str_replace('EXAKAT_VERSION', \Exakat::VERSION, $html);
        $html = str_replace('EXAKAT_BUILD', \Exakat::BUILD, $html);
        $html = str_replace('PROJECT_NAME', $config->project_name, $html);
        $html = str_replace('PROJECT_FAVICON', $faviconHtml, $html);

        file_put_contents($folder.'/'.$name.'/index.html', $html);
        
        foreach($summary as $titleUp => $section) {
            foreach($section as $title => $method) {
                if (method_exists($this, $method)) {
                    $html = $this->$method();
                } else {
                    print $html = 'Using default for '.$title."\n";
                }

                $filename = $this->makeFileName($title);
                
                $html = <<<HTML
<script language="javascript">
if (!document.getElementById("main")) {
    window.location.href = "../index.html#ajax/$filename";
}
</script >
<div class="row">
	<div id="breadcrumb" class="col-xs-12">
		<a href="#" class="show-sidebar">
			<i class="fa fa-bars"></i>
		</a>
		<ol class="breadcrumb pull-left">
			<li><a href="index.html">Dashboard</a></li>
			<li><a href="#ajax/About-This-Report.html">About This Report</a></li>
		</ol>
	</div>
</div>

<h4 class="page-header">$title</h4>
<div class="row">
	<div class="col-xs-12">
$html
    </div>
</div>
HTML;
                
                file_put_contents($folder.'/'.$name.'/ajax/'.$filename, 
                                  $html);
            }
        }

        return true;
    }//end generate()
    
    ////////////////////////////////////////////////////////////////////////////////////
    // Utilities
    ////////////////////////////////////////////////////////////////////////////////////
    private function makeSummary($summary, $level = 0) {
        if ($level === 0) {
            $html = '<ul class="nav main-menu">';

            foreach($summary as $title => $section) {
                $html .= <<<HTML
                <li class="dropdown">
					<a href="#" class="dropdown-toggle">
						<i class="fa fa-bar-chart-o"></i>
						<span class="hidden-xs">$title</span>
					</a>
HTML;
                $html .= $this->makeSummary($section, $level + 1);
            }

        $html .= '</ul>';

        } else {
            $html = '<ul class="dropdown-menu">';

            foreach($summary as $title => $section) {
                $filename = $this->makeFileName($title);
                $html .= <<<HTML
						<li><a href="ajax/$filename" class="exakat-link">$title</a></li>
HTML;
            }
            $html .= '</ul>';
        }

        return $html;
    }
    
    protected function makeFileName($title) {
        // must sync with Template/Section.php
        // @todo : remove this sync!
        return str_replace(array(' ', '(', ')', ':', '*', '.', '/', '&', '_', '|', '^', ','), 
                           array('-', '' , '' , '' , '' , '', '', '', '_', '', '', '' ),
                               $title).'.html';
    }

    private function copyDir($src, $dst) { 
        $dir = opendir($src); 
        mkdir($dst, Devoops::FOLDER_PRIVILEGES); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->copyDir($src . '/' . $file,$dst . '/' . $file); 
                } else { 
                    copy($src . '/' . $file, $dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 

    ////////////////////////////////////////////////////////////////////////////////////
    /// Formatting methods 
    ////////////////////////////////////////////////////////////////////////////////////
    private function formatSimpleTable($data, $css) {
        $th = '';
        
        if ($css->displayTitles === true) {
            $th .= '<tr>';
            foreach($css->titles as $title) {
                $th .= <<<HTML
<th>$title</th>

HTML;
        }
            $th .= "</tr>";
        }
        
        $text = <<<HTML
				<table class="table">
					<thead>
						<tr>
{$th}
						</tr>
					</thead>

													<tbody>

HTML;

        $readOrder = $css->readOrder;
        if (empty($readOrder)) {
            $readOrder = range(0, count($css->titles) - 1);
        }

        foreach($data as $v) {
            $row = '<tr>';
            foreach($readOrder as $V) {
                $row .= "<td>$v[$V]</td>\n";
            }
            $row .= "</tr>";

            $text .= $row;
        }
        $text .= <<<HTML
					</tbody>
				</table>

HTML;

        return $text;
    }

    private function formatText($text, $style = '') {
        $text = nl2br($text);
        
        if (!empty($style)) {
            $class = ' class="'.$style.'"';
        } else {
            $class = '';
        }

        return '<p'.$class.'>'.$text."</p>\n";
    }
    /// End of Formatting methods 

    ////////////////////////////////////////////////////////////////////////////////////
    /// Content methods
    ////////////////////////////////////////////////////////////////////////////////////
    private function AboutThisReport() {
        return $this->formatText( <<<Devoops
            This report has been build, thanks to the following other Open Source projects. 
            
			<div class="about-inner">
				<h3 class="page-header">Devoops</h4>
				<p>By the DevOOPS team : Open-source admin theme for you.</p>
				<p>Homepage - <a href="http://devoops.me" target="_blank">http://devoops.me</a></p>
				<p>Email - <a href="mailto:devoopsme@gmail.com">devoopsme@gmail.com</a></p>
				<p>Twitter - <a href="http://twitter.com/devoopsme" target="_blank">http://twitter.com/devoopsme</a></p>

				<h3 class="page-header">jQuery</h4>
				<p>By the jQuery Foundation</p>
				<p>Homepage - <a href="http://jquery.com/" target="_blank">http://jquery.com/</a></p>
				<p>Twitter - <a href="https://twitter.com/jQuery" target="_blank">https://twitter.com/jQuery</a></p>
			</div>
Devoops
);
    }

    private function NonProcessedFiles() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('File');
        $css->readOrder = $css->titles;
        
        $data = array();
        $res = $this->datastore->query('SELECT file FROM ignoredFiles');
        while($row = $res->fetchArray()) {
            if (empty($row['file'])) { continue; }

            $data[] = array('File' => $row['file']);
        }
        
        $return = $this->formatText( <<<TEXT
This is the list of processed files. Any file that is in the project, but not in the list below was omitted in the analyze. 

This may be due to configuration file, compilation error, wrong extension (including no extension). 
TEXT
, 'textLead');

        if (!empty($data)) {
           $return .= $this->formatSimpleTable($data, $css);
        } else {
           $return .= $this->formatText('All files and folders were used');
        }
        
        return $return;
    }

    
    private function ProcessedFiles() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('File');
        $css->readOrder = $css->titles;
        
        $data = array();
        $res = $this->datastore->query('SELECT file FROM files');
        while($row = $res->fetchArray()) {
            $data[] = array('File' => $row['file']);
        }
        
        return $this->formatText( <<<TEXT
This is the list of processed files. Any file that is in the project, but not in the list below was omitted in the analyze. 

This may be due to configuration file, compilation error, wrong extension (including no extension). 
TEXT
, 'textLead')
                .$this->formatSimpleTable($data, $css);
    }
    
    
}//end class