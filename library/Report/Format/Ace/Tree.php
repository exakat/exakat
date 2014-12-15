<?php

namespace Report\Format\Ace;

class Tree extends \Report\Format\Ace { 
    static public $tree_counter = 0;
    
    public function render($output, $data) {
        $output->pushToJsLibraries( array("assets/js/fuelux/fuelux.tree.min.js",));

        $counter = \Report\Format\Ace\Tree::$tree_counter++;
        
        $tree_data = $this->renderTreeData($data);
        
$js = <<<JS

var DataSourceTree = function(options) {
	this._data 	= options.data;
	this._delay = options.delay;
}

DataSourceTree.prototype.data = function(options, callback) {
	var self = this;
	var \$data = null;

	if(!("name" in options) && !("type" in options)){
		\$data = this._data;//the root tree
		callback({ data: \$data });
		return;
	}
	else if("type" in options && options.type == "folder") {
		if("additionalParameters" in options && "children" in options.additionalParameters)
			\$data = options.additionalParameters.children;
		else \$data = {}//no data
	}

    if(\$data != null)  //this setTimeout is only for mimicking some random delay
		setTimeout(function(){callback({ data: \$data });} , parseInt(Math.random() * 500) + 200);

};

$tree_data
var treeDataSource = new DataSourceTree({data: tree_data});

jQuery(function(\$){

		\$('#tree1').ace_tree({
			dataSource: treeDataSource ,
			multiSelect:true,
			loadingHTML:'<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>',
			'open-icon' : 'icon-minus',
			'close-icon' : 'icon-plus',
			'selectable' : false,
			'selected-icon' : '', //icon-ok
			'unselected-icon' : '', //icon-remove
		});
    });
JS;
        $output->pushToTheEnd($js);
        
        $text = <<<HTML
								<div class="widget-box span6">
									<div class="widget-header header-color-blue2">
										<h4 class="lighter smaller">{$this->css->title}</h4>
									</div>

									<div class="widget-body">
										<div class="widget-main padding-8">
											<div id="tree1" class="tree"></div>
										</div>
									</div>
								</div>
HTML;
        
        $output->push($text);
    }

    private function renderTreeData($data) {
/*
var tree_data = {
	'for-sale' : {name: 'For Sale', type: 'folder'}	,
	'vehicles' : {name: 'Vehicles', type: 'folder'}	,
	'rentals' : {name: 'Rentals', type: 'folder'}	,
	'real-estate' : {name: 'Real Estate', type: 'folder'}	,
	'pets' : {name: 'Pets', type: 'folder'}	,
	'tickets' : {name: 'Tickets', type: 'item'}	,
	'services' : {name: 'Services', type: 'item'}	,
	'personals' : {name: 'Personals', type: 'item'}
}

*/
        $return = "var tree_data = {\n";
        $end = '';

        foreach($data as $key => $value) {
            $id = $this->makeId($key);
            if (is_array($value)) {
                $return .= "	'$id' : {name: '$key', type: 'folder'}	,\n";
                $end .= $this->renderTreeData2($value, $key);
            } else {
                $return .= "	'$id' : {name: '$key', type: 'item'}	,\n";
            }
        }
        $return .= "}\n$end";

        return $return;
    }

    private function renderTreeData2($data, $name) {
/*

tree_data['rentals']['additionalParameters'] = {
	'children' : {
		'apartments-rentals' : {name: 'Apartments', type: 'item'},
		'office-space-rentals' : {name: 'Office Space', type: 'item'},
		'vacation-rentals' : {name: 'Vacation Rentals', type: 'item'}
	}
}

*/
        $return = "tree_data['".$this->makeId($name)."']['additionalParameters']= {\n	'children' : {\n";
        $end = '';

        foreach($data as $key => $value) {
            $id = $this->makeId($key);
                $return .= "	'$id' : {name: '$key <i class=\"".($value == 'Yes' ? 'icon-ok' : 'icon-ko')."\"></i>', type: 'item'},\n";
        }
        $return .= "	}\n}\n$end";

        return $return;
    }
    
    private function makeId($name) {
        return str_replace(' ', '-', strtolower($name));
    }
}

?>
