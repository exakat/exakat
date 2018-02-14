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
use Exakat\Config;
use Exakat\Datastore;
use Exakat\Data\Methods;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;
use XMLWriter;

class Devoops extends Reports {
    const FILE_FILENAME  = 'devoops';

    const FOLDER_PRIVILEGES = 0755;

    const NOT_RUN      = 'Not Run';
    const YES          = 'Yes';
    const NO           = 'No';
    const INCOMPATIBLE = 'Incompatible';

    protected $dump      = null; // Dump.sqlite

    protected $analyzers  = array(); // cache for analyzers [Title] = object

    public function generate($folder, $name = 'report') {
        $finalName = $name;
        $name = '.'.$name;

        if ($name === null) {
            return "Can't produce Devoops format to stdout";
        }

        // Clean final destination
        if ($folder.'/'.$finalName !== '/') {
            rmdirRecursive($folder.'/'.$finalName);
        }

        if (file_exists($folder.'/'.$finalName)) {
            display ($folder.'/'.$finalName." folder was not cleaned. Please, remove it before producing the report. Aborting report\n");
            return;
        }

        // Clean temporary destination
        if (file_exists($folder.'/'.$name)) {
            rmdirRecursive($folder.'/'.$name);
        }

        mkdir($folder.'/'.$name, Devoops::FOLDER_PRIVILEGES);
        mkdir($folder.'/'.$name.'/ajax', Devoops::FOLDER_PRIVILEGES);

        copyDir($this->config->dir_root.'/media/devoops/css', $folder.'/'.$name.'/css');
        copyDir($this->config->dir_root.'/media/devoops/img', $folder.'/'.$name.'/img');
        copyDir($this->config->dir_root.'/media/devoops/js', $folder.'/'.$name.'/js');
        copyDir($this->config->dir_root.'/media/devoops/plugins', $folder.'/'.$name.'/plugins');

        $this->dump      = new \Sqlite3($folder.'/dump.sqlite', \SQLITE3_OPEN_READONLY);
        // This is an overwriting. Leave it here.
        $this->datastore = new \Sqlite3($folder.'/datastore.sqlite', \SQLITE3_OPEN_READONLY);

        // Compatibility
        $compatibility = array('Compilation' => 'Compilation');
        foreach($this->config->other_php_versions as $code) {
            if ($code == 52) { continue; }

            $version = $code[0].'.'.substr($code, 1);
            $compatibility['Compatibility '.$version] = 'Compatibility';
        }

        // Analyze
        $analyze = array();
        $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 'CompatibilityPHP70', 'CompatibilityPHP71',
                              '"Dead code"', 'Security', 'Analyze');
        $analyzers = Analyzer::getThemeAnalyzers($themes);
        $themesList = '("'.implode('", "', $analyzers).'")';

        $res = $this->dump->query('SELECT * FROM resultsCounts WHERE count > 0 AND analyzer in '.$themesList);
        while($row = $res->fetchArray()) {
            $analyzer = Analyzer::getInstance($row['analyzer'], null, $this->config);

            $this->analyzers[$analyzer->getDescription()->getName()] = $analyzer;
            $analyze[$analyzer->getDescription()->getName()] = 'OneAnalyzer';
        }
        uksort($analyze, function ($a, $b) {
            return strtolower($a) > strtolower($b) ;
        });
        $analyze = array_merge(array('Results Counts' => 'AnalyzersResultsCounts'), $analyze);

        // Files
        $files = array();
        $res = $this->dump->query('SELECT DISTINCT file FROM results ORDER BY file');
        while($row = $res->fetchArray()) {
            $files[$row['file']] = 'OneFile';
        }
        $files = array_merge(array('Files Counts' => 'FilesResultsCounts'), $files);

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
                                           'PHP Bugfixes'           => 'Bugfixes',
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
        if (file_exists($this->config->dir_root.'/projects/'.$this->config->project.'/code/favicon.ico')) {
            // Should be checked and reported
            copy($this->config->dir_root.'/projects/'.$this->config->project.'/code/favicon.ico', $folder.'/'.$name.'/img/'.$this->config->project.'.ico');

            $faviconHtml = <<<HTML
<img src="img/{$this->config->project}.ico" class="img-circle" alt="{$this->config->project} logo" />
HTML;

            if (!empty($this->config->project_url)) {
                $faviconHtml = "<a href=\"{$this->config->project_url}\" class=\"avatar\">$faviconHtml</a>";
            }

            $faviconHtml = <<<HTML
				<div class="avatar">
					$faviconHtml
				</div>
HTML;
        }

        $html = file_get_contents($this->config->dir_root.'/media/devoops/index.exakat.html');
        $html = str_replace('<menu>', $summaryHtml, $html);

        $html = str_replace('EXAKAT_VERSION', Exakat::VERSION, $html);
        $html = str_replace('EXAKAT_BUILD', Exakat::BUILD, $html);
        $html = str_replace('PROJECT_NAME', $this->config->project_name, $html);
        $html = str_replace('PROJECT_FAVICON', $faviconHtml, $html);

        file_put_contents($folder.'/'.$name.'/index.html', $html);

        foreach($summary as $section) {
            foreach($section as $title => $method) {
                if (method_exists($this, $method)) {
                    $html = $this->$method($title);
                } else {
                    $html = 'Using default for '.$title."\n";
                    display($html);
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

        rename($folder.'/'.$name, $folder.'/'.$finalName);

        return '';
    }//end generate()

    ////////////////////////////////////////////////////////////////////////////////////
    // Utilities
    ////////////////////////////////////////////////////////////////////////////////////
    protected function makeSummary($summary, $level = 0) {
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

    protected function loadJson($file) {
        $fullpath = $this->config->dir_root.'/data/'.$file;

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

    protected function makeIcon($tag) {
        switch($tag) {
            case self::YES :
                return '<i class="fa fa-check"></i>';
            case self::NO :
                return '&nbsp;';
            case self::NOT_RUN :
                return '<i class="fa fa-times-circle-o"></i>';
            case self::INCOMPATIBLE :
                return '<i class="fa fa-minus-circle"></i>';
            default :
                return '&nbsp;';
        }
    }

    protected function reportStatus($count) {
        if ($count == Analyzer::VERSION_INCOMPATIBLE) {
            return '<i class="fa fa-stethoscope"></i>';
        } elseif ($count == Analyzer::CONFIGURATION_INCOMPATIBLE) {
            return '<i class="fa fa-stethoscope"></i>';
        } else {
            return $count;
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////
    /// Formatting methods
    ////////////////////////////////////////////////////////////////////////////////////
    protected function formatCamembert($data, $css) {
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

    protected function formatCompilationTable($data, $css) {
        $th = '<tr>';
        foreach($css->titles as $title) {
            $th .= <<<HTML
															<th>
																$title
															</th>

HTML;
        }
        $th .= '</tr>';

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
                        $row .= '<td><ul><li>'.implode('</li><li>', $V)."</li></ul></td>\n";
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

    protected function formatDashboard($data, $css) {
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

        return $this->formatRow($camembert, $infobox, $css).$this->formatRow($top5Severity, $top5Files, $css);
    }

    protected function formatDefinitions($data, $css) {
        $text = <<<HTML
													<dl id="dt-list-1" >
HTML;

        uksort($data, function ($a, $b) {
            return strtolower($a) > strtolower($b) ;
        });

        if (!empty($css->dt->class)) {
            $dt_class = ' class="'.$css->dt->class.'"';
        } else {
            $dt_class = '';
        }

        if (!empty($css->dd->class)) {
            $dd_class = ' class="'.$css->dd->class.'"';
        } else {
            $dd_class = '';
        }

        foreach($data as $name => $definition) {
            $id = str_replace(' ', '-', strtolower($name));
            $description = $this->prepareText($definition['description']);

            if (!empty($definition['clearphp'])) {
                $description .= "<br />\n<br />\nThis rule is named '<a href=\"https://github.com/dseguy/clearPHP/blob/master/rules/$definition[clearphp].md\">$definition[clearphp]</a>', in the clearPHP reference.";
            }

            $nameLink = $this->makeLink($name);
            $text .= "
														<dt$dt_class><a name=\"$id\"></a>$nameLink</dt>
														<dd$dd_class><p>$description</p></dd>";
        }

        $text .= <<<HTML
													</dl>
HTML;

        return $text;
    }

    protected function formatHashTableLinked($data, $css) {
        static $counter;

        if (!isset($counter)) {
            $counter = 1;
        } else {
            ++$counter;
        }

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
            if ($v['result'] == Analyzer::VERSION_INCOMPATIBLE) {
                $v['result'] = '';
                $icon = '<i class="fa fa-stethoscope"></i>';
            } elseif ($v['result'] == Analyzer::CONFIGURATION_INCOMPATIBLE) {
                $v['result'] = '';
                $icon = '<i class="fa fa-stethoscope"></i>';
            } elseif ($v['result'] === 0) {
                $v['result'] = '';
                $icon = '<i class="fa fa-check-square-o green"></i>';
            } else {
                $k = $this->makeLink($k);
                $v['result'] .= ' warnings';
                $icon = '<i class="fa fa-exclamation red"></i>';
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

    protected function formatHorizontal($data, $css) {
        static $counter;

        if (!isset($counter)) {
            $counter = 1;
        } else {
            ++$counter;
        }

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

    protected function formatInfobox($data, $css) {
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

    protected function formatRow($left, $right, $css) {
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

    protected function formatSectionedHashTable($data, $css) {
        static $counter;

        if (!isset($counter)) {
            $counter = 1;
        } else {
            ++$counter;
        }

        $text = <<<HTML
<table id="sectionedhashtable-{$counter}" class="table">
										<thead>
HTML;

        if ($css->displayTitles === true) {
            $text .= '<tr>';
            foreach($css->titles as $title) {
                $text .= <<<HTML
															<th>
																$title
															</th>

HTML;
            }
            $text .= '</tr>';
        }

        $text .= <<<HTML
										</thead>

										<tbody>
HTML;
        foreach($data as $k => $v) {
            $text .= "<tr class=\"primary\"><td>$k</td><td>&nbsp;</td></tr>\n";
            if (is_array($v)) {
                foreach($v as $k2 => $v2) {
                    $text .= "<tr><td>$k2</td><td>$v2</td></tr>\n";
                }
            }
        }
        $text .= <<<HTML
										</tbody>
									</table>
HTML;

        return $text;
    }

    protected function formatSectionedTable($data, $css) {
        static $counter;
        if (!isset($counter)) {
            $counter = 1;
        } else {
            ++$counter;
        }

        $text = <<<HTML
<table id="sectionedhashtable-{$counter}" class="table">
										<thead>
HTML;

        if ($css->displayTitles === true) {
            $text .= '<tr>';
            foreach($css->titles as $title) {
                $text .= <<<HTML
															<th>
																$title
															</th>

HTML;
            }
            $text .= '</tr>';
        }

        $text .= <<<HTML
										</thead>

										<tbody>
HTML;
        $readOrder = $css->readOrder;
        if (empty($readOrder)) {
            $readOrder = range(0, count($css->titles)-1);
        }

        foreach($data as $k => $v) {
            $text .= '<tr><td style="background-color: '.$css->backgroundColor.'">'.$k.'</td>'.str_repeat('<td style="background-color: '.$css->backgroundColor.'">&nbsp;</td>', count($css->titles) -1)."</tr>\n";
            if (empty($v)) {
                continue;
            }

            foreach($v as $v2) {
                $v2 = (array) $v2;
                $text .= '<tr>';
                foreach($readOrder as $id) {
                    $text .= "<td>$v2[$id]</td>\n";
                }
                $text .= "</tr>\n";
            }
        }
        $text .= <<<HTML
										</tbody>
									</table>
HTML;

        return $text;
    }


    protected function formatSimpleTable($data, $css) {
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
                $row .= "<td>".$this->makeHtml($v[$V])."</td>\n";
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

    protected function formatSimpleTableResultsCount($data, $css) {
        static $counter;
        if (!isset($counter)) {
            $counter = 1;
        } else {
            ++$counter;
        }

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
            if ($v[0] == 'Total') {
                continue;
            }
            // below 0 are errors
            if ($v[1] >= 0) {
                $v[0] = $this->makeLink($v[0]);
            }
            $v[1] = $this->reportStatus($v[1]);
            $text .= "<tr><td>{$v[0]}</td><td>{$v[1]}</td>";
            if (isset($v[2])) {
                $text .= "<td>{$v[2]}</td>";
            }
            $text .= "</tr>\n";
        }

        if (isset($v)) {
            $text .= "<tfoot><tr><td>{$v[0]}</td><td>{$v[1]}</td>";
        }
        if (isset($v[2])) {
            $text .= "<td>{$v[2]}</td>";
        }
        $text .= "</tr></tfoot>\n";

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

    protected function formatText($text, $style = '') {
        $text = $this->prepareText($text);

        if (!empty($style)) {
            $class = ' class="'.$style.'"';
        } else {
            $class = '';
        }

        return '<p'.$class.'>'.$text."</p>\n";
    }

    protected function formatTextLead($text) {
        $text = $this->prepareText($text);

        return "<article><p class=\"lead\">".$text."</p></article>\n"."<script src=\"plugins/readmore/readmore.js\"></script>\n"."<script src=\"plugins/readmore/jquery.mockjax.js\"></script>\n"."<script>$('article').readmore({collapsedHeight: 90});</script>\n";
    }

    protected function formatTop5($data, $css) {
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
            $severity = $this->makeLink($value['name']);
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

    protected function formatThemeList($list) {
        static $figure2Letters = array(1 => 'one',
                                       2 => 'two',
                                       3 => 'three',
                                       4 => 'four',
                                       5 => 'five',
                                       6 => 'six');
        if (isset($figure2Letters[count($list)])) {
            $count = $figure2Letters[count($list)];
        } else {
            $count = count($list);
        }

        $html = 'This analysis is part of '.$count.' theme'.(count($list) > 1 ? 's' : '').' : ';

        foreach($list as &$title) {
            $title = $this->makeLink($title, 'ajax/'.$this->makeFileName($title));
        }
        unset($title);
        return $html.implode(', ', $list);
    }

    protected function formatTree($data, $css) {
        $text = "<ul>\n";
        foreach($data as $k => $v) {
            $text .= "    <li>$k";

            $text .= "    <ul>\n";
            foreach($v as $k2 => $v2) {
                $text .= "        <li>$k2 ".$this->makeIcon($v2).'</li>';
            }
            $text .= "    </ul>\n";

            $text .= "</li>\n";
        }
        $text .= "</ul>\n";

        return $text;
    }

    /// End of Formatting methods

    ////////////////////////////////////////////////////////////////////////////////////
    /// Content methods
    ////////////////////////////////////////////////////////////////////////////////////
    protected function AboutThisReport() {
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

    protected function AlteredDirectives() {
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
        , 'textLead').$this->formatSimpleTable($data, $css);
    }

    protected function Analyzers() {
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

    protected function AnalyzersResultsCounts() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Label', 'Count', 'Severity');
        $css->readOrder = $css->titles;

        $themes = array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56', 'CompatibilityPHP70', 'CompatibilityPHP71',
                              '"Dead code"', 'Security', 'Analyze');
        $analyzers = Analyzer::getThemeAnalyzers($themes);
        $themesList = '("'.implode('", "', $analyzers).'")';

        $res = $this->dump->query(<<<SQL
SELECT analyzer, count(*) AS count, severity FROM results 
        WHERE analyzer IN $themesList 
        GROUP BY analyzer
        HAVING count > 0
SQL
        );
        $data = array();
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            $analyzer = Analyzer::getInstance($row[0], null, $this->config);
            $row[0] = $analyzer->getDescription()->getName();

            $data[] = $row;
        }

        return $this->formatSimpleTableResultsCount($data, $css);
    }

    protected function Appinfo() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('File');
        $css->readOrder = $css->titles;

        $extensions = array(
                    'PHP' => array(
                            'Short tags'                 => 'Structures/ShortTags',
                            'Echo tags <?='              => 'Php/EchoTagUsage',
                            'Incompilable'               => 'Php/Incompilable',

                            '@ operator'                 => 'Structures/Noscream',
                            'Alternative syntax'         => 'Php/AlternativeSyntax',
                            'Magic constants'            => 'Constants/MagicConstantUsage',
                            'halt compiler'              => 'Php/Haltcompiler',
                            'Assertions'                 => 'Php/AssertionUsage',

                            'Casting'                    => 'Php/CastingUsage',
                            'Resources'                  => 'Structures/ResourcesUsage',
                            'Nested Loops'               => 'Structures/NestedLoops',

                            'Autoload'                   => 'Php/AutoloadUsage',
                            'inclusion'                  => 'Structures/IncludeUsage',
                            'include_once'               => 'Structures/OnceUsage',
                            'Output control'             => 'Extensions/Extob',

                            'Goto'                       => 'Php/Gotonames',
                            'Labels'                     => 'Php/Labelnames',

                            'Coalesce'                   => 'Php/Coalesce',
                            'Null Coalesce'              => 'Php/NullCoalesce',

                            'File upload'                => 'Structures/FileUploadUsage',
                            'Environnement Variables'    => 'Php/UsesEnv',
                    ),

                    'Composer' => array(
                            'composer.json'              => 'Composer/UseComposer',
                            'composer autoload'          => 'Composer/Autoload',
                    ),

                    'Web' => array(
                            '$_GET, _POST...'            => 'Php/UseWeb',
                    ),

                    'CLI' => array(
                            '$argv, $argc'                 => 'Php/UseCli',
                            'CLI script'                   => 'Files/IsCliScript',
                    ),

                    // filled later
                    'Composer Packages' => array(),

                    'Namespaces' => array(
                            'Namespaces'              => 'Namespaces/Namespacesnames',
                            'Alias'                   => 'Namespaces/Alias',
                    ),

                    'Variables' => array(
                            'References'              => 'Variables/References',
                            'Array'                   => 'Arrays/Arrayindex',
                            'Multidimensional arrays' => 'Arrays/Multidimensional',
                            'Array short syntax'      => 'Arrays/ArrayNSUsage',
                            'List short syntax'       => 'Structures/ListShortSyntax',
                            'Variable variables'      => 'Variables/VariableVariables',

                            'PHP arrays'              => 'Arrays/Phparrayindex',

                            'Globals'                 => 'Structures/GlobalUsage',
                            'PHP SuperGlobals'        => 'Php/SuperGlobalUsage',
                    ),

                    'Functions' => array(
                            'Functions'                => 'Functions/Functionnames',
                            'Redeclared PHP Functions' => 'Functions/RedeclaredPhpFunction',
                            'Closures'             => 'Functions/Closures',

                            'Typehint'             => 'Functions/Typehints',
                            'Scalar Typehint'      => 'Php/ScalarTypehintUsage',
                            'Return Typehint'      => 'Php/ReturnTypehintUsage',
                            'Nullable Typehint'    => 'Php/UseNullableType',
                            'Static variables'     => 'Variables/StaticVariables',

                            'Function dereferencing'     => 'Structures/FunctionSubscripting',
                            'Constant scalar expression' => 'Structures/ConstantScalarExpression',
                            '... usage'                  => 'Php/EllipsisUsage',
                            'func_get_args'              => 'Functions/VariableArguments',

                            'Dynamic functioncall' => 'Functions/Dynamiccall',

                            'Recursive Functions'  => 'Functions/Recursive',
                            'Generator Functions'  => 'Functions/IsGenerator',
                            'Conditioned Function' => 'Functions/ConditionedFunctions',
                    ),

                    'Classes' => array(
                            'Classes'           => 'Classes/Classnames',
                            'Anonymous Classes' => 'Classes/Anonymous',
                            'Class aliases'     => 'Classes/ClassAliasUsage',

                            'Abstract classes'  => 'Classes/Abstractclass',
                            'Interfaces'        => 'Interfaces/Interfacenames',
                            'Traits'            => 'Traits/Traitnames',

                            'Static properties' => 'Classes/StaticProperties',

                            'Static methods'    => 'Classes/StaticMethods',
                            'Abstract methods'  => 'Classes/Abstractmethods',
                            'Final methods'     => 'Classes/Finalmethod',

                            'Class constants'   => 'Classes/ConstantDefinition',
                            'Overwritten constants' => 'Classes/OverwrittenConst',

                            'Magic methods'     => 'Classes/MagicMethod',
                            'Cloning'           => 'Classes/CloningUsage',
                            'Dynamic class call'=> 'Classes/VariableClasses',

                            'PHP 4 constructor' => 'Classes/OldStyleConstructor',
                            'Multiple class in one file' => 'Classes/MultipleClassesInFile',
                    ),

                    'Constants' => array(
                            'Constants'           => 'Constants/ConstantUsage',
                            'Boolean'             => 'Type/BooleanValue',
                            'Null'                => 'Type/NullValue',
                            'Variable Constant'   => 'Constants/VariableConstant',
                            'PHP constants'       => 'Constants/PhpConstantUsage',
                            'PHP Magic constants' => 'Constants/MagicConstantUsage',
                            'Conditioned constant'=> 'Constants/ConditionedConstants',
                    ),

                    'Numbers' => array(
                            'Integers'            => 'Type/Integer',
                            'Hexadecimal'         => 'Type/Hexadecimal',
                            'Octal'               => 'Type/Octal',
                            'Binary'              => 'Type/Binary',
                            'Real'                => 'Type/Real',
                    ),

                    'Strings' => array(
                            'Heredoc'             => 'Type/Heredoc',
                            'Nowdoc'              => 'Type/Nowdoc',
                     ),

                    'Errors' => array(
                            'Throw exceptions'    => 'Php/ThrowUsage',
                            'Try...Catch'         => 'Php/TryCatchUsage',
                            'Multiple catch'      => 'Structures/MultipleCatch',
                            'Multiple Exceptions' => 'Exceptions/MultipleCatch',
                            'Finally'             => 'Structures/TryFinally',
                            'Trigger error'       => 'Php/TriggerErrorUsage',
                            'Error messages'      => 'Structures/ErrorMessages',
                     ),

                    'External systems' => array(
                            'System'           => 'Structures/ShellUsage',
                            'Files'            => 'Structures/FileUsage',
                            'LDAP'             => 'Extensions/Extldap',
                            'mail'             => 'Structures/MailUsage',
                     ),

                    'Extensions' => array(
                            'ext/amqp'       => 'Extensions/Extamqp',
                            'ext/apache'     => 'Extensions/Extapache',
                            'ext/apc'        => 'Extensions/Extapc',
                            'ext/apcu'       => 'Extensions/Extapcu',
                            'ext/array'      => 'Extensions/Extarray',
                            'ext/ast'        => 'Extensions/Extast',
                            'ext/bcmath'     => 'Extensions/Extbcmath',
                            'ext/bzip2'      => 'Extensions/Extbzip2',
                            'ext/cairo'      => 'Extensions/Extcairo',
                            'ext/calendar'   => 'Extensions/Extcalendar',
                            'ext/com'        => 'Extensions/Extcom',
                            'ext/crypto'     => 'Extensions/Extcrypto',
                            'ext/ctype'      => 'Extensions/Extctype',
                            'ext/curl'       => 'Extensions/Extcurl',
                            'ext/cyrus'      => 'Extensions/Extcyrus',
                            'ext/date'       => 'Extensions/Extdate',
                            'ext/dba'        => 'Extensions/Extdba',
                            'ext/dio'        => 'Extensions/Extdio',
                            'ext/dom'        => 'Extensions/Extdom',
                            'ext/eaccelerator' => 'Extensions/Exteaccelerator',
                            'ext/enchant'    => 'Extensions/Extenchant',
                            'ext/ereg'       => 'Extensions/Extereg',
                            'ext/event'      => 'Extensions/Extevent',
                            'ext/ev'         => 'Extensions/Extev',
                            'ext/exif'       => 'Extensions/Extexif',
                            'ext/expect'     => 'Extensions/Extexpect',
                            'ext/fann'       => 'Extensions/Extfann',
                            'ext/fdf'        => 'Extensions/Extfdf',
                            'ext/ffmpeg'     => 'Extensions/Extffmpeg',
                            'ext/file'       => 'Extensions/Extfile',
                            'ext/fileinfo'   => 'Extensions/Extfileinfo',
                            'ext/filter'     => 'Extensions/Extfilter',
                            'ext/fpm'        => 'Extensions/Extfpm',
                            'ext/ftp'        => 'Extensions/Extftp',
                            'ext/gd'         => 'Extensions/Extgd',
                            'ext/gearman'    => 'Extensions/Extgearman',
                            'ext/geoip'      => 'Extensions/Extgeoip',
                            'ext/gettext'    => 'Extensions/Extgettext',
                            'ext/gmagick'    => 'Extensions/Extgmagick',
                            'ext/gmp'        => 'Extensions/Extgmp',
                            'ext/gnupg'      => 'Extensions/Extgnupg',
                            'ext/hash'       => 'Extensions/Exthash',
                            'ext/php_http'   => 'Extensions/Exthttp',
                            'ext/ibase'      => 'Extensions/Extibase',
                            'ext/iconv'      => 'Extensions/Exticonv',
                            'ext/iis'        => 'Extensions/Extiis',
                            'ext/imagick'    => 'Extensions/Extimagick',
                            'ext/imap'       => 'Extensions/Extimap',
                            'ext/info'       => 'Extensions/Extinfo',
                            'ext/inotify'    => 'Extensions/Extinotify',
                            'ext/intl'       => 'Extensions/Extintl',
                            'ext/json'       => 'Extensions/Extjson',
                            'ext/kdm5'       => 'Extensions/Extkdm5',
                            'ext/ldap'       => 'Extensions/Extldap',
                            'ext/libevent'   => 'Extensions/Extlibevent',
                            'ext/libxml'     => 'Extensions/Extlibxml',
                            'ext/mail'       => 'Extensions/Extmail',
                            'ext/mailparse'  => 'Extensions/Extmailparse',
                            'ext/math'       => 'Extensions/Extmath',
                            'ext/mbstring'   => 'Extensions/Extmbstring',
                            'ext/mcrypt'     => 'Extensions/Extmcrypt',
                            'ext/memcache'   => 'Extensions/Extmemcache',
                            'ext/memcached'  => 'Extensions/Extmemcached',
                            'ext/ming'       => 'Extensions/Extming',
                            'ext/mongo'      => 'Extensions/Extmongo',
                            'ext/mssql'      => 'Extensions/Extmssql',
                            'ext/mysql'      => 'Extensions/Extmysql',
                            'ext/mysqli'     => 'Extensions/Extmysqli',
                            'ext/ob'         => 'Extensions/Extob',
                            'ext/oci8'       => 'Extensions/Extoci8',
                            'ext/odbc'       => 'Extensions/Extodbc',
                            'ext/opcache'    => 'Extensions/Extopcache',
                            'ext/openssl'    => 'Extensions/Extopenssl',
                            'ext/parsekit'   => 'Extensions/Extparsekit',
                            'ext/pcntl'      => 'Extensions/Extpcntl',
                            'ext/pcre'       => 'Extensions/Extpcre',
                            'ext/pdo'        => 'Extensions/Extpdo',
                            'ext/pgsql'      => 'Extensions/Extpgsql',
                            'ext/phalcon'    => 'Extensions/Extphalcon',
                            'ext/phar'       => 'Extensions/Extphar',
                            'ext/posix'      => 'Extensions/Extposix',
                            'ext/proctitle'  => 'Extensions/Extproctitle',
                            'ext/pspell'     => 'Extensions/Extpspell',
                            'ext/readline'   => 'Extensions/Extreadline',
                            'ext/recode'     => 'Extensions/Extrecode',
                            'ext/redis'      => 'Extensions/Extredis',
                            'ext/reflexion'  => 'Extensions/Extreflection',
                            'ext/runkit'     => 'Extensions/Extrunkit',
                            'ext/sem'        => 'Extensions/Extsem',
                            'ext/session'    => 'Extensions/Extsession',
                            'ext/shmop'      => 'Extensions/Extshmop',
                            'ext/simplexml'  => 'Extensions/Extsimplexml',
                            'ext/snmp'       => 'Extensions/Extsnmp',
                            'ext/soap'       => 'Extensions/Extsoap',
                            'ext/sockets'    => 'Extensions/Extsockets',
                            'ext/spl'        => 'Extensions/Extspl',
                            'ext/sqlite'     => 'Extensions/Extsqlite',
                            'ext/sqlite3'    => 'Extensions/Extsqlite3',
                            'ext/sqlsrv'     => 'Extensions/Extsqlsrv',
                            'ext/ssh2'       => 'Extensions/Extssh2',
                            'ext/standard'   => 'Extensions/Extstandard',
                            'ext/tidy'       => 'Extensions/Exttidy',
                            'ext/tokenizer'  => 'Extensions/Exttokenizer',
                            'ext/trader'     => 'Extensions/Exttrader',
                            'ext/wddx'       => 'Extensions/Extwddx',
                            'ext/wikidiff2'  => 'Extensions/Extwikidiff2',
                            'ext/wincache'   => 'Extensions/Extwincache',
                            'ext/xcache'     => 'Extensions/Extxcache',
                            'ext/xdebug'     => 'Extensions/Extxdebug',
                            'ext/xdiff'      => 'Extensions/Extxdiff',
                            'ext/xhprof'     => 'Extensions/Extxhprof',
                            'ext/xml'        => 'Extensions/Extxml',
                            'ext/xmlreader'  => 'Extensions/Extxmlreader',
                            'ext/xmlrpc'     => 'Extensions/Extxmlrpc',
                            'ext/xmlwriter'  => 'Extensions/Extxmlwriter',
                            'ext/xsl'        => 'Extensions/Extxsl',
                            'ext/yaml'       => 'Extensions/Extyaml',
                            'ext/yis'        => 'Extensions/Extyis',
                            'ext/zip'        => 'Extensions/Extzip',
                            'ext/zlib'       => 'Extensions/Extzlib',
                            'ext/zmq'        => 'Extensions/Extzmq',
        //                          'ext/skeleton'   => 'Extensions/Extskeleton',
                    ),
                );

        // collecting information for Extensions
        $themed = Analyzer::getThemeAnalyzers('Appinfo');
        $res = $this->dump->query('SELECT analyzer, count FROM resultsCounts WHERE analyzer IN ("'.implode('", "', $themed).'")');
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
            $res = $this->dump->query('SELECT fullcode FROM results WHERE analyzer = "Composer/PackagesNames"');
            while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $data['Composer Packages'][] = $row['fullcode'];
            }
        } else {
            unset($data['Composer Packages']);
        }

        $return = $this->formatText( <<<TEXT
This is an overview of your application. 

<ul>
<li>Ticked <i class="fa fa-check"></i> information are features used in the application.</li>
<li>Non-ticked are feature that are not in use in the application.</li>
<li>Crossed <i class="fa fa-minus-circle"></i> features are not compatibile with the PHP version used, or its configuration. </li>
<li>Crossed <i class="fa fa-times-circle-o"></i> information were not tested.</li>
</ul>

TEXT
        , 'textLead');
        $return .= $this->formatTree($data, $css);

        return $return;
    }

    protected function AuditConfiguration() {
        $css = new \Stdclass();
        $css->displayTitles = false;
        $css->titles = array(0, 1);
        $css->readOrder = $css->titles;

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

        $datastore = new Datastore($this->config);

        $info[] = array('Number of PHP files', $datastore->getHash('files'));
        $info[] = array('Number of lines of code', $datastore->getHash('loc'));
        $info[] = array('Number of lines of code with comments', $datastore->getHash('locTotal'));

        $info[] = array('Report production date', date('r', strtotime('now')));

        $php = new Phpexec($this->config->phpversion, $this->config->{'php'.str_replace('.', '', $this->config->phpversion)});
        $info[] = array('PHP used', $php->getActualVersion().' (version '.$this->config->phpversion.' configured)');
        $info[] = array('Ignored files/folders', implode(', ', $this->config->ignore_dirs));

        $info[] = array('Exakat version', Exakat::VERSION.' ( Build '.Exakat::BUILD.') ');

        return $this->formatSimpleTable($info, $css);
    }

    protected function Bugfixes() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Title', 'Solved In 7.0', 'Solved In 5.6', 'Solved In 5.5', 'Solved In php-src', 'bugs.php.net', 'CVE');
        $css->readOrder = $css->titles;

        $data = new Methods($this->config);
        $bugfixes = $data->getBugFixes();

        $found = $this->dump->query('SELECT * FROM results WHERE analyzer = "Php/MiddleVersion"');
        $reported = array();
        $info = array();

        $rows = array();
        while($row = $found->fetchArray()) {
            $rows[strtolower(substr($row['fullcode'], 0, strpos($row['fullcode'], '(')))] = $row;
        }

        foreach($bugfixes as $bugfix) {
            if (!empty($bugfix['function'])) {
                if (!isset($rows[$bugfix['function']])) { continue; }

                $cve = $this->Bugfixes_cve($bugfix['cve']);

                $info[] = array('title'       => $bugfix['title'],
                                'solvedIn70'  => $bugfix['solvedIn70']  ? $bugfix['solvedIn70']  : '-',
                                'solvedIn56'  => $bugfix['solvedIn56']  ? $bugfix['solvedIn56']  : '-',
                                'solvedIn55'  => $bugfix['solvedIn55']  ? $bugfix['solvedIn55']  : '-',
                                'solvedInDev' => $bugfix['solvedInDev'] ? $bugfix['solvedInDev'] : '-',
                                'bug'         => '<a href="https://bugs.php.net/bug.php?id='.$bugfix['bugs'].'">#'.$bugfix['bugs'].'</a>',
                                'cve'         => $cve,
                                );
            } elseif (!empty($bugfix['analyzer'])) {
                $subanalyze = $this->dump->querySingle('SELECT COUNT(*) FROM results WHERE analyzer = "'.$bugfix['analyzer'].'"');

                $cve = $this->Bugfixes_cve($bugfix['cve']);

                if ($subanalyze > 0) {
                    $info[] = array('title'       => $bugfix['title'],
                                    'solvedIn70'  => $bugfix['solvedIn70']  ? $bugfix['solvedIn70'] : '-',
                                    'solvedIn56'  => $bugfix['solvedIn56']  ? $bugfix['solvedIn56'] : '-',
                                    'solvedIn55'  => $bugfix['solvedIn55']  ? $bugfix['solvedIn55'] : '-',
                                    'solvedInDev' => $bugfix['solvedInDev'] ? $bugfix['solvedInDev'] : '-',
                                    'bug'         => 'ext/'.$bugfix['extension'],
                                    'cve'         => $cve,
                                    );
                }
            } else {
                continue; // ignore. Possibly some mis-configuration
            }
        }

        return $this->formatCompilationTable($info, $css);
    }

    protected function Bugfixes_cve($cve) {
        if (!empty($cve)) {
            if (strpos($cve, ', ') !== false) {
                $cves = explode(', ', $cve);
                $cveHtml = array();
                foreach($cves as $cve) {
                    $cveHtml[] = '<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name='.$cve.'">'.$cve.'</a>';
                }
                $cveHtml = implode(', ', $cveHtml);
            } else {
                $cveHtml = '<a href="https://cve.mitre.org/cgi-bin/cvename.cgi?name='.$cve.'">'.$cve.'</a>';
            }
        } else {
            $cveHtml = '-';
        }

        return $cveHtml;
    }

    protected function Compilation() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Version', 'Count', 'Fraction', 'Files', 'Errors');
        $css->readOrder = $css->titles;

        $total = $this->datastore->querySingle('SELECT value FROM hash WHERE key = "files"');
        $info = array();
        foreach($this->config->other_php_versions as $suffix) {
            $res = $this->datastore->querySingle('SELECT name FROM sqlite_master WHERE type="table" AND name="compilation'.$suffix.'"');
            if (!$res) {
                continue; // Table was not created
            }

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

                $total_error = count($files).' ('.number_format(count($files) / $total * 100, 0).'%)';
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

    protected function Compatibility($title) {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Feature', 'Status');
        $css->readOrder = $css->titles;

        $list = Analyzer::getThemeAnalyzers(str_replace(array(' ', '.'), array('PHP', ''), $title));

        $res = $this->datastore->query('SELECT analyzer, counts FROM analyzed');
        $counts = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $counts[$row['analyzer']] = $row['counts'];
        }

        foreach($list as $l) {
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$l.'.ini');
            if (isset($counts[$l])) {
                $info[ $ini['name'] ] = array('result' => (int) $counts[$l]);
            } else {
                $info[ $ini['name'] ] = array('result' => -1);
            }
        }

        return $this->formatHashTableLinked($info, $css);
    }

    protected function Dashboard($title) {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Library', 'Folder', 'Home page');
        $css->readOrder = $css->titles;

        $titles = array('Code Smells'         => 'Analyze',
                        'Dead Code'           => 'Dead code',
                        'Security'            => 'Security',
                        'Performances'        => 'Performances');

        $list = Analyzer::getThemeAnalyzers($titles[$title]);
        $where = 'WHERE analyzer in ("'.implode('", "', $list).'")';

        $res = $this->dump->query('SELECT severity, count(*) AS nb FROM results '.$where.' GROUP BY severity ORDER BY severity');
        $severities = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $severities[$row['severity']] = array('severity' => $row['severity'],
                                                  'count'    => $row['nb']);
        }

        $res = $this->dump->query('SELECT analyzer, count(*) AS nb, severity AS severity FROM results '.$where.' GROUP BY analyzer');
        $listBySeverity = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $ini = parse_ini_file($this->config->dir_root.'/human/en/'.$row['analyzer'].'.ini');
            $listBySeverity[] = array('name'  => $ini['name'],
                                      'severity' => $row['severity'],
                                      'count' => $row['nb']);
        }
        uasort($listBySeverity, function ($a, $b) {
            $s = array('Critical' => 6, 'Major' => 5, 'Middle' => 4, 'Minor' => 3, 'None' => 0);
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

    protected function Directives() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Directive', 'Suggestion', 'Description');
        $css->backgroundColor = '#DDDDDD';
        $css->readOrder = array('name', 'suggested', 'documentation');

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

        $data = array();
        $res = $this->dump->query(<<<SQL
SELECT analyzer FROM resultsCounts 
    WHERE ( analyzer LIKE "Extensions/Ext%" OR 
            analyzer IN ("Structures/FileUploadUsage", "Php/UsesEnv"))
        AND count > 0
SQL
        );
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if ($row['analyzer'] == 'Structures/FileUploadUsage') {
                $data['File Upload'] = (array) json_decode(file_get_contents($this->config->dir_root.'/data/directives/fileupload.json'));
            } elseif ($row['analyzer'] == 'Php/UsesEnv') {
                $data['Environnement'] = (array) json_decode(file_get_contents($this->config->dir_root.'/data/directives/env.json'));
            } else {
                $ext = substr($row['analyzer'], 14);
                if (in_array($ext, $directives)) {
                    $data[$ext] = (array) json_decode(file_get_contents($this->config->dir_root.'/data/directives/'.$ext.'.json'));
                }
            }
        }

        return $this->formatText( <<<TEXT
This is an overview of the recommended directives for your application. 
The most important directives have been collected here, for a quick review. 
The whole list of directive is available as a link to the manual, when applicable. 

When an extension is missing from the list below, either it as no specific configuration directive, 
or it is not used by the current code. 

TEXT
        , 'textLead').$this->formatSectionedTable($data, $css);
    }

    protected function Documentation() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        //        $css->titles = array('Library', 'Folder', 'Home page');
        //        $css->readOrder = $css->titles;

        $data = array();
        foreach($this->analyzers as $analyzer) {
            $description = $analyzer->getDescription();
            $data[$description->getName()] = array('description' => $description->getDescription(),
                                                   'clearphp'    => $description->getClearPHP());

        }

        return $this->formatDefinitions($data, $css);
    }

    protected function DynamicCode() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Code');
        $css->readOrder = $css->titles;

        $data = array();
        $res = $this->dump->query('SELECT fullcode FROM results WHERE analyzer="Structures/DynamicCode"');
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
            , 'textLead').$this->formatSimpleTable($data, $css);
        }
    }

    protected function ErrorMessages() {
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
        , 'textLead').$this->formatSimpleTable($data, $css);
    }

    protected function ExternalConfigFiles() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Service', 'File', 'Home page');
        $css->readOrder = $css->titles;

        $data = array();
        $res = $this->datastore->query('SELECT name AS Service, file AS File, homepage AS url FROM configFiles');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (empty($row['url'])) {
                $row['Home page'] = '';
            } else {
                $row['Home page'] = "<a href=\"".$row['url']."\">".$row['Service']." <i class=\"fa fa-sign-out\"></i></a>";
            }
            $data[] = $row;
        }

        $return = $this->formatText( <<<TEXT
List services being used in this code repository, based on config files that are committed. For example, a .git folder is an artefact of a GIT repository.
TEXT
        , 'textLead');

        $return .= $this->formatSimpleTable($data, $css);

        return $return;    }

    protected function ExternalLibraries() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Library', 'Folder', 'Home page');
        $css->readOrder = $css->titles;

        $externallibraries = $this->loadJson('externallibraries.json');

        $data = array();
        $res = $this->datastore->query('SELECT library AS Library, file AS Folder FROM externallibraries ORDER BY library');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $url = $externallibraries->{strtolower($row['Library'])}->homepage;
            if (empty($url)) {
                $row['Home page'] = '';
            } else {
                $row['Home page'] = "<a href=\"".$url."\">".$row['Library']." <i class=\"fa fa-sign-out\"></i></a>";
            }
            $data[] = $row;
        }

        $return = $this->formatText( <<<TEXT
This is the list of analyzers that were run. Those that doesn t have result will not be listed in the 'Analyzers' section.

This may be due to PHP version or PHP configuration incompatibilities.

TEXT
        , 'textLead').$this->formatSimpleTable($data, $css);

        return $return;
    }

    protected function FilesResultsCounts() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('File', 'Count');
        $css->readOrder = $css->titles;

        $res = $this->dump->query(<<<SQL
SELECT file, count(*) AS count FROM results 
        WHERE analyzer IN $this->themesList
        GROUP BY file
SQL
        );
        $data = array();
        while($row = $res->fetchArray(\SQLITE3_NUM)) {
            $data[] = $row;
        }

        return $this->formatSimpleTableResultsCount($data, $css);
    }

    protected function GlobalVariablesList() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Code', 'File', 'Line');
        $css->readOrder = $css->titles;

        $data = array();
        $sqlQuery = 'SELECT fullcode AS Code, file AS File, line AS Line  FROM results WHERE analyzer="Structures/GlobalInGlobal" ORDER BY fullcode';
        $res = $this->dump->query($sqlQuery);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = $row;
        }

        return $this->formatText( <<<TEXT
Here are the global variables, including the implicit ones : any variable that are used in the global scope, outside methods, are implicitely globals.
TEXT
        , 'textLead').$this->formatSimpleTable($data, $css);
    }

    protected function OneAnalyzer($title) {
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
            $return .= '<p>clearPHP : <a href="https://github.com/dseguy/clearPHP/blob/master/rules/'.$clearPHP.'.md">'.$clearPHP.'</a></p>';
        }

        $return .= $this->formatThemeList($analyzer->getThemes());
        $data = array();
        $sqlQuery = 'SELECT fullcode as Code, file AS File, line AS Line FROM results WHERE analyzer="'.$this->dump->escapeString($analyzer->getInBaseName()).'"';
        $res = $this->dump->query($sqlQuery);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        $return .= $this->formatHorizontal($data, $css);

        return $return;
    }

    protected function OneFile($title) {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('Code', 'Analyzer', 'Line');
        $css->sort = $css->titles;

        $return = $this->formatText('All results for the file : '.$title, 'textLead');

        $data = array();
        $sqliteTitle = $this->dump->escapeString($title);
        $sqlQuery = <<<SQL
SELECT fullcode as Code, analyzer AS Analyzer, line AS Line FROM results 
    WHERE file="$sqliteTitle" AND
          analyzer IN $this->themesList

SQL;
        $res = $this->dump->query($sqlQuery);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $analyzer = Analyzer::getInstance($row['Analyzer'], null, $this->config);
            if ($analyzer->getDescription() === null) {
                $row['File'] = '';
            } else {
                $row['File'] = $analyzer->getDescription()->getName();
            }
            $data[] = $row;
        }
        $return .= $this->formatHorizontal($data, $css);

        return $return;
    }

    protected function NonProcessedFiles() {
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

    protected function ProcessedFiles() {
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
        , 'textLead').$this->formatSimpleTable($data, $css);
    }

    protected function Stats() {
        $css = new \Stdclass();
        $css->displayTitles = true;
        $css->titles = array('File');
        $css->readOrder = $css->titles;

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
                            'Throw'               => 'Throw',
                            'Try'                 => 'Try',
                            'Catch'               => 'Catch',
                            'Finally'             => 'Finally',
                            'Yield'               => 'Yield',
                            '?  :'                => 'Ternary',
                            '?: '                 => 'Php/Coalesce',
                            '??'                  => 'Php/NullCoalesce',
                            'Variables constants' => 'Constants/VariableConstants',
                            'Variables variables' => 'Variables/VariableVariable',
                            'Variables functions' => 'Functions/Dynamiccall',
                            'Variables classes'   => 'Classes/VariableClasses',
                    ),
                );

        $data = array();
        foreach($extensions as $section => $hash) {
            $data[$section] = array();
            foreach($hash as $name => $ext) {
                if (strpos($ext, '/') === false) {
                    $res = $this->dump->query('SELECT count FROM atomsCounts WHERE atom="'.$ext.'"');
                    $d = $res->fetchArray(\SQLITE3_ASSOC);
                    $d = (int) $d['count'];
                } else {
                    $res = $this->dump->query('SELECT count FROM resultsCounts WHERE analyzer="'.$ext.'"');
                    $d = $res->fetchArray(\SQLITE3_ASSOC);
                    $d = (int) $d['count'];
                }
                $data[$section][$name] = $d === -2 ? 'N/A' : $d;
            }
        }

        return $this->formatText( <<<TEXT
These are various stats of different structures in your application.
TEXT
        , 'textLead').$this->formatSectionedHashTable($data, $css);
    }

    protected function prepareText($text) {
        $html = nl2br(trim($text));

        $html = preg_replace('$(https?://\S+)\.?\s$', '<a href=\"\1\">\1</a>', $html);

        // link functions/features to PHP manual
        if (preg_match_all('$[a-z_0-9]+\(\)$s', $html, $r)) {
            $html = preg_replace('$([a-z_0-9]+)\(\)$s', '<a href="http://www.php.net/\1">\1()</a>', $html);
        }

        // highlight PHP code

        if (preg_match('$(<\?php)$s', $html, $r)) {
            $html = preg_replace_callback('$(<\?php.*?\?'.'>)$s', function ($r) { return substr(highlight_string(str_replace('<br />', '', $r[0]), true), 6, -8); }, $html);
        }

        return $html;
    }

}//end class

?>