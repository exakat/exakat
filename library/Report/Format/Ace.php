<?php

namespace Report\Format;

class Ace { 
    private $output = '';
    private $finalJs = '';
    private $jsLibraries = array();
    
    private $last = '';
    private $files = array();
    protected static $analyzer = null;
    private $summary = null;
    
    public function render($output, $data) {
        $output->push(" Text for ".get_class($this)."\n");
    }
    
    public function push($render) {
        $this->output .= $render;
    }
    
    public function pushToTheEnd($render) {
        $this->finalJs .= "$render\n";
    }

    public function pushToJsLibraries($library) {
        if (is_array($library)) {
            $this->jsLibraries = array_merge($this->jsLibraries, $library);
        } else {
            $this->jsLibraries[] = $library;
        }
    }

    public function reset() {
        $this->output  = "";
        $this->finalJs = "";
        $this->jsLibraries = array();
    }
    
    public function setSummaryData($data) {
        $this->summary = $data;
    }
    
    public function toFile($filename) {
        $ext = $this->getExtension();
        $dir = substr($filename, 0, - (1 + strlen($ext)));
        if (file_exists($dir)) {
            shell_exec("rm -rf $dir"); 
        }
        mkdir($dir, 0755);
        print shell_exec('cp -r media/ace-admin/assets '.$dir);
        
        $total = 0;
        foreach($this->files as $name => $html) {
            $total += file_put_contents($dir.'/'.$name, $html);
        }
        
        shell_exec('cd '.dirname($dir).'; zip -r web web 2 >> /dev/null');
        
        return $total;
    }
    
    protected function toFile2($filename, $data) {
        $section_name = $data->getName();
        
        $table_count = \Report\Format\Ace\Horizontal::$horizontal_counter; 
        
        $renderSidebar = new \Report\Format\Ace\SummarySidebar();
        $sidebar = new static();
        
        $renderSidebar->render($sidebar, $this->summary->getContent());
        
        if (count($this->jsLibraries) > 0) {
            $this->jsLibraries = "        <script src=\"".join("\"></script>\n        <script src=\"", $this->jsLibraries)."\"></script>\n";
        } else {
            $this->jsLibraries = " /* No extra libraries */ ";
        }

        $sidebar = <<<HTML
			<div class="sidebar" id="sidebar">
{$sidebar->getOutput()}

				<div class="sidebar-collapse" id="sidebar-collapse">
					<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
				</div>

				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>
HTML;



        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Exakat Audit Report</title>

		<meta name="description" content="Static &amp; Dynamic Tables" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!--basic styles-->

		<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="assets/css/font-awesome.min.css" />

		<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->

		<!--page specific plugin styles-->

		<!--fonts-->

		<link rel="stylesheet" href="assets/css/ace-fonts.css" />

		<!--ace styles-->

		<link rel="stylesheet" href="assets/css/ace.min.css" />
		<link rel="stylesheet" href="assets/css/ace-responsive.min.css" />
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />

		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!--inline styles related to this page-->

		<!--ace settings handler-->

		<script src="assets/js/ace-extra.min.js"></script>
	</head>

	<body>
		<div class="navbar" id="navbar">

			<div class="navbar-inner">
				<div class="container-fluid">
					<a href="#" class="brand">
						<small>
							<i class="icon-leaf"></i>
							Exakat Audit Report
						</small>
					</a><!--/.brand-->
				</div><!--/.container-fluid-->
			</div><!--/.navbar-inner-->
		</div>

		<div class="main-container container-fluid">
			<a class="menu-toggler" id="menu-toggler" href="#">
				<span class="menu-text"></span>
			</a>

$sidebar

			<div class="main-content">
				<div class="page-content">

					<div class="row-fluid">
						<div class="span12">
							<!--PAGE CONTENT BEGINS-->
							<div class="row-fluid">
								<h1 class="header smaller lighter blue">$section_name</h1>
{$this->output}
				</div><!--/.page-content-->

				<div class="ace-settings-container" id="ace-settings-container">
					<div class="btn btn-app btn-mini btn-warning ace-settings-btn" id="ace-settings-btn">
						<i class="icon-cog bigger-150"></i>
					</div>

					<div class="ace-settings-box" id="ace-settings-box">
						<div>
							<div class="pull-left">
								<select id="skin-colorpicker" class="hide">
									<option data-skin="default" value="#438EB9">#438EB9</option>
									<option data-skin="skin-1" value="#222A2D">#222A2D</option>
									<option data-skin="skin-2" value="#C6487E">#C6487E</option>
									<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
								</select>
							</div>
							<span>&nbsp; Choose Skin</span>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
							<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
							<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
							<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
							<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
						</div>
					</div>
				</div><!--/#ace-settings-container-->
			</div><!--/.main-content-->
		</div><!--/.main-container-->

		<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
			<i class="icon-double-angle-up icon-only bigger-110"></i>
		</a>

		<!--basic scripts-->

		<!--[if !IE]>-->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!--<![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="assets/js/bootstrap.min.js"></script>

		<!--page specific plugin scripts-->

    	{$this->jsLibraries}

		<!--ace scripts-->

		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>

		<!--inline scripts related to this page-->

		<script type="text/javascript">
			jQuery(function($) {
    			{$this->finalJs}
			})
			
		</script>
	</body>
</html>

HTML;
        $this->files[$filename] = $html;

        $this->reset();
        
        return true;
    }
    
    public function getRenderer($class) {
        $fullclass = "\\Report\\Format\\Ace\\$class";
        
        $this->classes[$class] = new $fullclass();
        return $this->classes[$class];
    }

    public function getExtension() {
        return 'html';
    }

    public function setAnalyzer($name) {
        \Report\Format\Ace::$analyzer = $name;
    }
    
    public function getOutput() {
        return $this->output;
    }
}

?>