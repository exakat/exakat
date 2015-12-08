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
    
    private $analyzers = array(); // cache for analyzers
    
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
            $compatibility['Compatibility '.$version] = 'Compatibility';
        }

        // Analyze
        $analyze = array();
        $res = $this->dump->query('SELECT * FROM resultsCounts WHERE count > 0');
        while($row = $res->fetchArray()) {
            $analyzer = \Analyzer\Analyzer::getInstance($row['analyzer']);
            
            $this->analyzers[$analyzer->getDescription()->getName()] = $analyzer;
            $analyze[$analyzer->getDescription()->getName()] = 'OneAnalyzer';
        }
        ksort($analyze);
        $analyze = array_merge(array('Results Counts' => 'AnalyzeResultCounts'), $analyze);

        // Files
        $files = array();
        $res = $this->dump->query('SELECT DISTINCT file FROM results ORDER BY file');
        while($row = $res->fetchArray()) {
            $files[$row['file']] = 'OneFile';
        }
        $files = array_merge(array('Files Counts' => 'FileResultCounts'), $files);
        
        $summary = array(
            'Report presentation' => array('Audit configuration' => 'AuditConfiguration'),
            'Analysis'            => array('Code Smells'         => 'Dashboard',
                                           'Dead Code'           => 'Dashboard',
                                           'Security'            => 'Dashboard',
                                           'Performances'        => 'Dashboard'),
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
                    $html = $this->$method($title);
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
    
    protected function loadJson($file) {
        $config = \Config::factory();
        $fullpath = $config->dir_root.'/data/'.$file;

        if (!file_exists($fullpath)) {
            return null;
        }

        $jsonFile = json_decode(file_get_contents($fullpath));
        
        return $jsonFile;
    }

    protected function makeLink($title, $file = null) {
        if ($file === null) {
            $file = 'ajax/'.$this->makeFileName($title);
        }
        $title = $this->makeHtml($title);
        return "<a href=\"$file\" class=\"exakat-link\">$title</a>";
    }

    protected function makeHtml($text) {
        return nl2br(trim(htmlentities($text, ENT_COMPAT | ENT_HTML401 , 'UTF-8')));
    }

    ////////////////////////////////////////////////////////////////////////////////////
    /// Formatting methods 
    ////////////////////////////////////////////////////////////////////////////////////
    private function formatCamembert($data, $css) {
        $datajs = '';
        foreach($data as $k => $v) {
            $datajs .= "{label: \"$k\", value: $v[count]},\n";
        }
        
        $html = <<<HTML
 <label class="label label-success">Pie Chart</label>
      <div id="pie-chart" style="height: 200px;" ></div>

<script type="text/javascript">
function DrawAllMorrisCharts(){
Morris.Donut({
  element: 'pie-chart',
  colors: [
    '#1424b8',
    '#0aa623',
    '#940f3f',
    '#148585',
    '#098215',
    '#b86c14',
    '#b83214'
  ],  
  data: [
    $datajs
  ]
});
}
$(document).ready(function() {
	// Load required scripts and draw graphs
	LoadMorrisScripts(DrawAllMorrisCharts);
	WinMove();
});
</script>

HTML;

        return $html;
    }
    
    private function formatCompilationTable($data, $css) {
        $th = '<tr>';
        foreach($css->titles as $title) {
            $th .= <<<HTML
															<th>
																$title
															</th>

HTML;
        }
        $th .= "</tr>";
        
        $text = <<<HTML
												<table class="table">
													<thead>
														<tr>
{$th}
														</tr>
													</thead>

													<tbody>

HTML;
        foreach($data as $v) {
            $row = '<tr>';
            foreach($v as $V) {
                if( is_array($V)) {
                    if (empty($V)) {
                        $row .= "<td>&nbsp;</td>\n";
                    } else {
                        $row .= '<td><ul><li>'.join('</li><li>', $V)."</li></ul></td>\n";
                    }
                } else {
                    $row .= "<td>$V</td>\n";
                }
            }
            $row .= '</tr>';

            $text .= $row;
        }
        $text .= <<<HTML
													</tbody>
												</table>
HTML;
        
        return $text;
    }
    
    private function formatDashboard($data, $css) {
        $camembert = $this->formatCamembert($data['upLeft'], $css);
        $infobox = $this->formatInfobox($data['upRight'], $css);

        $css = new \Stdclass();
        $css->title = 'List by Severity';
        $css->titles = array('Analyzer', 'Count', 'Severity');
        $top5Severity = $this->formatTop5($data['downLeft'], $css);

        $css = new \Stdclass();
        $css->title = 'List by Files';
        $css->titles = array('Analyzer', 'Count');
        $top5Files = $this->formatTop5($data['downRight'], $css);
        
        return $this->formatRow($camembert, $infobox, $css) . 
               $this->formatRow($top5Severity, $top5Files, $css);
    }
    
    private function formatHashTableLinked($data, $css) {
        static $counter;
        
        if (!isset($counter)) {
            $counter = 1;
        }

        $js = <<<JS
					var oTable1 = \$('#hashtable-{$counter}').dataTable( {
					"aoColumns": [
					  null, null
					] } );


JS;
//        $output->pushToTheEnd($js);

        $text = <<<HTML
<table class="table table-bordered table-striped table-hover table-heading table-datatable" id="hashtable-{$counter}">
										<thead>
HTML;
        
        if ($css->displayTitles === true) {
            $text .= '<tr>';
            foreach($css->titles as $title) {
                $text .= <<<HTML
															<th>$title</th>

HTML;
            }
            $text .= '</tr>';
        }

$text .= <<<HTML
										</thead>

										<tbody>
HTML;
        foreach($data as $k => $v) {
            if ($v['result'] == \Analyzer\Analyzer::VERSION_INCOMPATIBLE) {
                $v['result'] = '';
                $icon = '<i class="fa fa-stethoscope"></i>';
            } elseif ($v['result'] == \Analyzer\Analyzer::CONFIGURATION_INCOMPATIBLE) {
                $v['result'] = '';
                $icon = '<i class="fa fa-stethoscope"></i>';
            } elseif ($v['result'] === 0) {
                $icon = '<i class="fa fa-check-square-o green"></i>';
                $v['result'] = '';
            } else {
                $k = $this->makeLink($k);
                $icon = '<i class="fa fa-exclamation red"></i>';
                $v['result'] .= ' warnings';
            }
            $text .= '<tr><td>'.$k.'</td><td>'.$icon.' '.$v['result']."</td></tr>\n";
        }
        $text .= <<<HTML
										</tbody>
									</table>

<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	$('#hashtable-{$counter}').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
		"sPaginationType": "bootstrap",
		"oLanguage": {
			"sSearch": "",
			"sLengthMenu": '_MENU_'
		}
	});
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	WinMove();
});
</script>

HTML;
        
        return $text;
    }
    
    private function formatHorizontal($data, $css) {
        static $counter;
        
        if (!isset($counter)) {
            $counter = 1;
        }

        $js = <<<JS
    				var oTable1 = \$('#horizontal-{$counter}').dataTable( {
	    			"aoColumns": [
		    	      null, null, null, null
			    	] } );


JS;

        $html = <<<HTML
							<p>
								<table id="horizontal-{$counter}" class="table table-bordered table-striped table-hover table-heading table-datatable">
									<thead>
HTML;

        if ($css->displayTitles === true) {
            $html .= '<tr>';
            foreach($css->titles as $title) {
                $html .= <<<HTML
															<th>
																$title
															</th>
HTML;
        }
            $html .= '</tr>';
        }
        $html .= <<<HTML
									</thead>
									<tbody>
HTML;

        foreach($data as $row) {
            $row['Code'] = $this->makeHtml($row['Code']);
            if (empty($row['Code'])) {
                $row['Code'] = '&nbsp;';
            }
            
            $row['File'] = $this->makeLink($row['File']);
$html .= <<<HTML

										<tr>
											<td><pre class="prettyprint linenums">{$row['Code']}</pre></td>
											<td>{$row['File']}</td>
											<td>{$row['Line']}</td>
										</tr>
HTML;
            }


        $html .= <<<HTML
									</tbody>
								</table>
							</p>
<script type="text/javascript">
// Run Datables plugin and create 3 variants of settings
function AllTables(){
	$('#horizontal-{$counter}').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"sDom": "<'box-content'<'col-sm-6'f><'col-sm-6 text-right'l><'clearfix'>>rt<'box-content'<'col-sm-6'i><'col-sm-6 text-right'p><'clearfix'>>",
		"sPaginationType": "bootstrap",
		"oLanguage": {
			"sSearch": "",
			"sLengthMenu": '_MENU_'
		}
	});
}

$(document).ready(function() {
	// Load Datatables and run plugin on tables 
	LoadDataTablesScripts(AllTables);
	// Add Drag-n-Drop feature
	WinMove();
});
</script>

HTML;

        return $html;
    }
    
    private function formatInfobox($data, $css) {
        /*
        $text = <<<HTML
<div class="row">
HTML;
        $colors = $this->css->colors;
        
        $i = -1;
        foreach($data as $id => $row) {
            $i = ++$i % count($colors);
            $color = $colors[$i];
            
            $text .= <<<HTML
  <div class="col-md-1">
    {$row['icon']}&nbsp;{$row['number']}&nbsp;{$row['content']}
  </div>
HTML;

        }

            $text .= <<<HTML
								</div>

HTML;
    */
        $html = '&nbsp;';

        return $html;
    }
    
    private function formatRow($left, $right, $css) {
        $html = <<<HTML
        <div class="row">
        	<div class="col-xs-6">
                $left
            </div>
        	<div class="col-xs-6">
                $right
			</div>
		</div>
HTML;

        return $html;
    }

    private function formatSimpleTable($data, $css) {
        $th = '';
        
        if ($css->displayTitles === true) {
            $th .= '<tr>';
            foreach($css->titles as $title) {
                $th .= <<<HTML
<th>$title</th>

HTML;
        }
            $th .= '</tr>';
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
            $row .= '</tr>';

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

    private function formatTextLead($text) {
        $text = nl2br($text);

        return "<article><p class=\"lead\">".$text."</p></article>\n".
               "<script src=\"plugins/readmore/readmore.js\"></script>\n".
               "<script src=\"plugins/readmore/jquery.mockjax.js\"></script>\n".
               "<script>$('article').readmore({collapsedHeight: 90});</script>\n";
    }
    
    private function formatTop5($data, $css) {
        $html = '<p>'.$css->title."</p>\n";
        $html .= <<<HTML
<table class="table table-striped">
					<thead>
						<tr>
HTML;

        foreach($css->titles as $columnHeader) {
            $html .= "<th>$columnHeader</th>\n";
        }
        
        $html .= <<<HTML
						</tr>
					</thead>
					<tbody>
HTML;

        foreach($data as $value) {
            // @note This is the same getId() than in Section::getId()
            if ($value['severity'] == '') {
                $severity = $this->makeLink($value['name']);
            } else {
                $severity = $this->makeLink($value['name']);
            }
            $html .= <<<HTML
                    <tr>
						<td>$severity</td>
						<td>{$value['count']}</td>
						<td><span class="label label-info arrowed-right arrowed-in">{$value['severity']}</span></td>
                    </tr>

HTML;
        }
        
        $html .= <<<HTML
					</tbody>
				</table>

HTML;

        return $html;
    }

    private function formatThemeList($list) {
        $html = 'This analyze is part of those themes : ';
        
        foreach($list as &$title) {
            $title = $this->makeLink($title, 'ajax/'.$this->makeFileName($title));
        }
        return $html . join(', ', $list);
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

    private function AlteredDirectives() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Directive');
        $css->readOrder = $css->titles;
        
        $data = array();
        $res = $this->dump->query('SELECT fullcode FROM results WHERE analyzer="Php/DirectivesUsage"');
        while($row = $res->fetchArray()) {
            $data[] = array('Directive' => $row['fullcode']);
        }
        
        return $this->formatText( <<<TEXT
This is an overview of the directives that are modified inside the application's code. 
TEXT
, 'textLead')
                .$this->formatSimpleTable($data, $css);
    }
    
    private function Analyzers() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Analyzer');
        $css->readOrder = $css->titles;
        
        $data = array();
        $res = $this->datastore->query('SELECT analyzer FROM analyzed WHERE counts >= 0');
        while($row = $res->fetchArray()) {
            if (empty($row['analyzer'])) { continue; }

            $data[] = array('Analyzer' => $row['analyzer']);
        }
        
        $return = $this->formatText( <<<TEXT
This is the list of analyzers that were run. Those that doesn't have result will not be listed in the 'Analyzers' section.

This may be due to PHP version or PHP configuration incompatibilities.
TEXT
, 'textLead');

        $return .= $this->formatSimpleTable($data, $css);
        
        return $return;
    }
    
    private function AuditConfiguration() {
        $config = \Config::factory();
        
        $css = new \Stdclass();
        $css->displayTitles = false;
        $css->titles = array(0, 1);
        $css->readOrder = $css->titles;
        
        $info = array();
        $info[] = array('Code name', $config->project_name);
        if (!empty($config->project_description)) {
            $info[] = array('Code description', $config->project_description);
        }
        if (!empty($config->project_packagist)) {
            $info[] = array('Packagist', '<a href="https://packagist.org/packages/'.$config->project_packagist.'">'.$config->project_packagist.'</a>');
        }
        if (!empty($config->project_url)) {
            $info[] = array('Home page', '<a href="'.$config->project_url.'">'.$config->project_url.'</a>');
        }
        if (file_exists($config->projects_root.'/projects/'.$config->project.'/code/.git/config')) {
            $gitConfig = file_get_contents($config->projects_root.'/projects/'.$config->project.'/code/.git/config');
            preg_match('#url = (\S+)\s#is', $gitConfig, $r);
            $info[] = array('Git URL', $r[1]);
            
            $res = shell_exec('cd '.$config->projects_root.'/projects/'.$config->project.'/code/; git branch');
            $info[] = array('Git branch', trim($res));

            $res = shell_exec('cd '.$config->projects_root.'/projects/'.$config->project.'/code/; git rev-parse HEAD');
            $info[] = array('Git commit', trim($res));
        } else {
            $info[] = array('Repository URL', 'Downloaded archive');
        }

        $datastore = new \Datastore(\Config::factory());
        
        $info[] = array('Number of PHP files', $datastore->getHash('files'));
        $info[] = array('Number of lines of code', $datastore->getHash('loc'));
        $info[] = array('Number of lines of code with comments', $datastore->getHash('locTotal'));

        $info[] = array('Report production date', date('r', strtotime('now')));
        
        $php = new \PhpExec($config->phpversion);
        $info[] = array('PHP used', $php->getActualVersion().' (version '.$config->phpversion.' configured)');
        $info[] = array('Ignored files/folders', join(', ', $config->ignore_dirs));
        
        $info[] = array('Exakat version', \Exakat::VERSION. ' ( Build '. \Exakat::BUILD . ') ');
        
        return $this->formatSimpleTable($info, $css);
    }

    private function Compilation() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Version', 'Count', 'Fraction', 'Files', 'Errors');
        $css->readOrder = $css->titles;
        
        $config = \Config::Factory();

        $total = $this->datastore->querySingle('SELECT value FROM hash WHERE key = "files"');
        $info = array();
        foreach($config->other_php_versions as $suffix) {
            $res = $this->datastore->query('SELECT file FROM compilation'.$suffix);
            $files = array();
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $files[] = $row['file'];
            }
            $version = $suffix[0].'.'.substr($suffix, 1);
            if (empty($files)) {
                $files       = 'No compilation error found.';
                $errors      = 'N/A';
                $total_error = 'None';
            } else {
                $res = $this->datastore->query('SELECT error FROM compilation'.$suffix);
                $readErrors = array();
                while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                    $readErrors[] = $row['error'];
                }
                $errors      = array_count_values($readErrors);
                $errors      = array_keys($errors);
                $errors      = array_keys(array_count_values($errors));

                $total_error = count($files).' (' .number_format(count($files) / $total * 100, 0). '%)';
                $files       = array_keys(array_count_values($files));
            }
            
            $array = array('version'       => $version,
                           'total'         => $total,
                           'total_error'   => $total_error,
                           'files'         => $files,
                           'errors'        => $errors,
                           );

            $info[] = $array;
        }
        
        return $this->formatCompilationTable($info, $css);
    }

    private function Compatibility($title) {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Feature', 'Status');
        $css->readOrder = $css->titles;
        
        $list = \Analyzer\Analyzer::getThemeAnalyzers(str_replace(array(' ', '.'), array('PHP', ''), $title));
        
        $begin = microtime(true);
        $res = $this->datastore->query('SELECT analyzer, counts FROM analyzed');
        $counts = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[$row['analyzer']] = $row['counts'];
        }
        foreach($list as $l) {
            $ini = parse_ini_file('./human/en/'.$l.'.ini');
            $info[ $ini['name']] = array('result' => (int) $counts[$l]);
        }
        $end = microtime(true);

        return $this->formatHashTableLinked($info, $css);
    }

    private function Dashboard($title) {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Library', 'Folder', 'Home page');
        $css->readOrder = $css->titles;
        
        $titles = array('Code Smells'         => 'Analyze',
                        'Dead Code'           => 'Dead code',
                        'Security'            => 'Security',
                        'Performances'        => 'Performances');
        
        $list = \Analyzer\Analyzer::getThemeAnalyzers($titles[$title]);
        $where = 'WHERE analyzer in ("'.join('", "', $list).'")';

        $res = $this->dump->query('SELECT severity, count(*) AS nb FROM results '.$where.' GROUP BY severity ORDER BY severity');
        $severities = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $severities[$row['severity']] = array('severity' => $row['severity'], 
                                                  'count'    => $row['nb']);
        }

        $res = $this->dump->query('SELECT analyzer, count(*) AS nb, severity AS severity FROM results '.$where.' GROUP BY analyzer');
        $listBySeverity = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $ini = parse_ini_file('./human/en/'.$row['analyzer'].'.ini');
            $listBySeverity[] = array('name'  => $ini['name'],
                                      'severity' => $row['severity'], 
                                      'count' => $row['nb']);
        }
        uasort($listBySeverity, function ($a, $b) {
            $s = ['Major' => 5, 'Middle' => 4, 'Minor' => 3, 'None' => 0];
            if ($s[$a['severity']] > $s[$b['severity']]) {
                return -1;
            } elseif ($s[$a['severity']] < $s[$b['severity']]) {
                return 1;
            } else {
                return 0;
            }
        });
        $listBySeverity = array_slice($listBySeverity, 0, 5);

        $res = $this->dump->query('SELECT file, count(*) AS nb FROM results '.$where.' GROUP BY file ORDER BY count(*) DESC LIMIT 5');
        $listByFile = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $listByFile[] = array('name'  => $row['file'],
                                  'severity' => '',
                                  'count' => $row['nb'],
                                  );
        }
        
        $info = array('upLeft'    => $severities,
                      'upRight'   => '&nbsp;',
                      'downLeft'  => $listBySeverity,
                      'downRight' => $listByFile);
        
        return $this->formatDashboard($info, $css);
    }

    private function DynamicCode() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Code');
        $css->readOrder = $css->titles;
        
        $data = array();
        $res = $this->dump->query('SELECT fullcode FROM results WHERE analyzer="Structures/DynamicCalls"');
        while($row = $res->fetchArray()) {
            $data[] = array('Code' => $row['fullcode']);
        }
        
        if (count($data) == 0) {
            return $this->formatText( <<<'TEXT'
No dynamic calls where found in the code. Dynamic calls may be one of the following : 
<ul>
    <li>Constant<br />
    <ul>
        <li>define('CONSTANT_NAME', $value);</li>
        <li>constant('Constant name');</li>
    </ul></li>

    <li>Variables<br />
    <ul>
        <li>$$variablevariable</li>
        <li>${$variablevariable}</li>
    </ul></li>

    <li>Properties<br />
    <ul>
        <li>$object->$propertyName</li>
        <li>$object->{$propertyName}</li>
        <li>$object->{'property'.'Name'}</li>
    </ul></li>

    <li>Methods<br />
    <ul>
        <li>$object->$methodName()</li>
        <li>call_user_func(array($object, $methodName), $arguments)</li>
    </ul></li>

    <li>Static Constants<br />
    <ul>
        <li>constant('StaticClass::ConstantName');</li>
    </ul></li>

    <li>Static Properties<br />
    <ul>
        <li>$class::$propertyName</li>
        <li>$class::{$propertyName}</li>
        <li>$class::{'property'.'Name'}</li>
    </ul></li>

    <li>Static Methods<br />
    <ul>
        <li>$class::$methodName()</li>
        <li>call_user_func(array('Class', $methodName), $arguments)</li>
    </ul></li>

</ul>

TEXT
);
        } else {
            return $this->formatText( <<<TEXT
This is the list of dynamic call. They are not checked by the static analyzer, and the analysis may be completed with a manual check of that list.
TEXT
, 'textLead')
                  .$this->formatSimpleTable($data, $css);
        }
    }
    
    private function ErrorMessages() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Message');
        $css->readOrder = $css->titles;
        
        $data = array();
        $res = $this->dump->query('SELECT fullcode FROM results WHERE analyzer="Structures/ErrorMessages"');
        while($row = $res->fetchArray()) {
            $data[] = array('Message' => $row['fullcode']);
        }
        
        return $this->formatText( <<<TEXT
Error message when an error is reported in the code. Those messages will be read by whoever is triggering the error, and it has to be helpful. 

It is a good excercice to read the messages out of context, and try to understand what is about.

Error messages are spotted via die, exit or exception. 
TEXT
, 'textLead')
                .$this->formatSimpleTable($data, $css);
    }
        
    private function ExternalLibraries() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Library', 'Folder', 'Home page');
        $css->readOrder = $css->titles;

        $externallibraries = $this->loadJson('externallibraries.json');

        $data = array();
        $res = $this->datastore->query('SELECT library AS Library, file AS Folder FROM externallibraries ORDER BY library');
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $url = $externallibraries->{strtolower($row['Library'])}->homepage;
            if (empty($url)) {
                $row['Home page'] = '';
            } else {
                $row['Home page'] = "<a href=\"".$url."\">".$row['Library']." <i class=\"fa fa-sign-out\"></i></a>";
            }
            $data[] = $row;
        }
        
        $return = $this->formatText( <<<TEXT
This is the list of analyzers that were run. Those that doesn't have result will not be listed in the 'Analyzers' section.

This may be due to PHP version or PHP configuration incompatibilities.
TEXT
, 'textLead');

        $return .= $this->formatSimpleTable($data, $css);
        
        return $return;
    }

    private function GlobalVariablesList() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Variable');
        $css->readOrder = $css->titles;
        
        $data = array();
        $res = $this->dump->query('SELECT fullcode FROM results WHERE analyzer="Structures/GlobalInGlobal"');
        while($row = $res->fetchArray()) {
            $data[] = array('Variable' => $row['fullcode']);
        }
        
        return $this->formatText( <<<TEXT
Here are the global variables, including the implicit ones : any variable that are used in the global scope, outside methods, are implicitely globals.
TEXT
, 'textLead')
                .$this->formatSimpleTable($data, $css);
    }
    
    private function OneAnalyzer($title) {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Code', 'File', 'Line');
        $css->sort = $css->titles;

        $analyzer = $this->analyzers[$title];
        
        $description = $analyzer->getDescription()->getDescription();
        if ($description == '') {
            $description = 'No documentation yet';
        }
        $return = $this->formatTextLead($description);

        if ($clearPHP = $analyzer->getDescription()->getClearPHP()) {
            $return .= $this->formatText('clearPHP : <a href="https://github.com/dseguy/clearPHP/blob/master/rules/'.$clearPHP.'.md">'.$clearPHP.'</a><br />', 'textLead');
        }

        $return .= $this->formatThemeList($analyzer->getThemes());
        $data = array();
        $sqlQuery = 'SELECT fullcode as Code, file AS File, line AS Line FROM results WHERE analyzer="'.$this->dump->escapeString($analyzer->getInBaseName()).'"';
        $res = $this->dump->query($sqlQuery);
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        $return .= $this->formatHorizontal($data, $css);
        
        return $return;
    }

    private function OneFile($title) {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Code', 'Analyzer', 'Line');
        $css->sort = $css->titles;

        $return = $this->formatText('All results for the file : '.$title, 'textLead');

        $data = array();
        $sqlQuery = 'SELECT fullcode as Code, analyzer AS Analyzer, line AS Line FROM results WHERE file="'.$this->dump->escapeString($title).'"';
        $res = $this->dump->query($sqlQuery);
        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $analyzer = \Analyzer\Analyzer::getInstance($row['Analyzer']);
            $row['File'] = $analyzer->getDescription()->getName();
            $data[] = $row;
        }
        $return .= $this->formatHorizontal($data, $css);
        
        return $return;
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