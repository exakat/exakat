if(! ('ace' in window) ) window['ace'] = {}
jQuery(function($) {
	//at some places we try to use 'tap' event instead of 'click' if jquery mobile plugin is available
	window['ace'].click_event = $.fn.tap ? "tap" : "click";
});

jQuery(function($) {
	//ace.click_event defined in ace-elements.js
	ace.handle_side_menu(jQuery);

	ace.enable_search_ahead(jQuery);	

	ace.general_things(jQuery);//and settings

	ace.widget_boxes(jQuery);

	/**
	//make sidebar scrollbar when it is fixed and some parts of it is out of view
	//>> you should include jquery-ui and slimscroll javascript files in your file
	//>> you can call this function when sidebar is clicked to be fixed
	$('.nav-list').slimScroll({
		height: '400px',
		distance:0,
		size : '6px'
	});
	*/
});



ace.handle_side_menu = function($) {
	$('#menu-toggler').on(ace.click_event, function() {
		$('#sidebar').toggleClass('display');
		$(this).toggleClass('display');
		return false;
	});
	//mini
	var $minimized = $('#sidebar').hasClass('menu-min');
	$('#sidebar-collapse').on(ace.click_event, function(){
		$minimized = $('#sidebar').hasClass('menu-min');
		ace.settings.sidebar_collapsed(!$minimized);//@ ace-extra.js
	});

	var touch = "ontouchend" in document;
	//opening submenu
	$('.nav-list').on(ace.click_event, function(e){
		//check to see if we have clicked on an element which is inside a .dropdown-toggle element?!
		//if so, it means we should toggle a submenu
		var link_element = $(e.target).closest('a');
		if(!link_element || link_element.length == 0) return;//if not clicked inside a link element
		
		$minimized = $('#sidebar').hasClass('menu-min');
		
		if(! link_element.hasClass('dropdown-toggle') ) {//it doesn't have a submenu return
			//just one thing before we return
			//if sidebar is collapsed(minimized) and we click on a first level menu item
			//and the click is on the icon, not on the menu text then let's cancel event and cancel navigation
			//Good for touch devices, that when the icon is tapped to see the menu text, navigation is cancelled
			//navigation is only done when menu text is tapped
			if($minimized && ace.click_event == "tap" &&
				link_element.get(0).parentNode.parentNode == this /*.nav-list*/ )//i.e. only level-1 links
			{
					var text = link_element.find('.menu-text').get(0);
					if( e.target != text && !$.contains(text , e.target) )//not clicking on the text or its children
					  return false;
			}

			return;
		}
		//
		var sub = link_element.next().get(0);

		//if we are opening this submenu, close all other submenus except the ".active" one
		if(! $(sub).is(':visible') ) {//if not open and visible, let's open it and make it visible
		  var parent_ul = $(sub.parentNode).closest('ul');
		  if($minimized && parent_ul.hasClass('nav-list')) return;
		  
		  parent_ul.find('> .open > .submenu').each(function(){
			//close all other open submenus except for the active one
			if(this != sub && !$(this.parentNode).hasClass('active')) {
				$(this).slideUp(200).parent().removeClass('open');
				
				//uncomment the following line to close all submenus on deeper levels when closing a submenu
				//$(this).find('.open > .submenu').slideUp(0).parent().removeClass('open');
			}
		  });
		} else {
			//uncomment the following line to close all submenus on deeper levels when closing a submenu
			//$(sub).find('.open > .submenu').slideUp(0).parent().removeClass('open');
		}

		if($minimized && $(sub.parentNode.parentNode).hasClass('nav-list')) return false;

		$(sub).slideToggle(200).parent().toggleClass('open');
		return false;
	 })
}



ace.general_things = function($) {
 $('.ace-nav [class*="icon-animated-"]').closest('a').on('click', function(){
	var icon = $(this).find('[class*="icon-animated-"]').eq(0);
	var $match = icon.attr('class').match(/icon\-animated\-([\d\w]+)/);
	icon.removeClass($match[0]);
	$(this).off('click');
 });
 
 $('.nav-list .badge[title],.nav-list .label[title]').tooltip({'placement':'right'});



 //simple settings

 $('#ace-settings-btn').on(ace.click_event, function(){
	$(this).toggleClass('open');
	$('#ace-settings-box').toggleClass('open');
 });

 
 $('#ace-settings-navbar').on('click', function(){
	ace.settings.navbar_fixed(this.checked);//@ ace-extra.js
 }).get(0).checked = ace.settings.is('navbar', 'fixed')

 $('#ace-settings-sidebar').on('click', function(){
	ace.settings.sidebar_fixed(this.checked);//@ ace-extra.js
 }).get(0).checked = ace.settings.is('sidebar', 'fixed')
 
 $('#ace-settings-breadcrumbs').on('click', function(){
	ace.settings.breadcrumbs_fixed(this.checked);//@ ace-extra.js
 }).get(0).checked = ace.settings.is('breadcrumbs', 'fixed')


 //Switching to RTL (right to left) Mode
 $('#ace-settings-rtl').removeAttr('checked').on('click', function(){
	ace.switch_direction(jQuery);
 });


 $('#btn-scroll-up').on(ace.click_event, function(){
	var duration = Math.max(100, parseInt($('html').scrollTop() / 3));
	$('html,body').animate({scrollTop: 0}, duration);
	return false;
 });
 
  try {
	$('#skin-colorpicker').ace_colorpicker();
  } catch(e) {}

  $('#skin-colorpicker').on('change', function(){
	var skin_class = $(this).find('option:selected').data('skin');

	var body = $(document.body);
	body.removeClass('skin-1 skin-2 skin-3');


	if(skin_class != 'default') body.addClass(skin_class);

	if(skin_class == 'skin-1') {
		$('.ace-nav > li.grey').addClass('dark');
	}
	else {
		$('.ace-nav > li.grey').removeClass('dark');
	}

	if(skin_class == 'skin-2') {
		$('.ace-nav > li').addClass('no-border margin-1');
		$('.ace-nav > li:not(:last-child)').addClass('light-pink').find('> a > [class*="icon-"]').addClass('pink').end().eq(0).find('.badge').addClass('badge-warning');
	}
	else {
		$('.ace-nav > li').removeClass('no-border margin-1');
		$('.ace-nav > li:not(:last-child)').removeClass('light-pink').find('> a > [class*="icon-"]').removeClass('pink').end().eq(0).find('.badge').removeClass('badge-warning');
	}

	if(skin_class == 'skin-3') {
		$('.ace-nav > li.grey').addClass('red').find('.badge').addClass('badge-yellow');
	} else {
		$('.ace-nav > li.grey').removeClass('red').find('.badge').removeClass('badge-yellow');
	}
 });
 
}



ace.widget_boxes = function($) {
	$('.page-content,#page-content').delegate('.widget-toolbar > [data-action]' , 'click', function(ev) {
		ev.preventDefault();

		var $this = $(this);
		var $action = $this.data('action');
		var $box = $this.closest('.widget-box');

		if($box.hasClass('ui-sortable-helper')) return;

		if($action == 'collapse') {
			var $body = $box.find('.widget-body');
			var $icon = $this.find('[class*=icon-]').eq(0);
			var $match = $icon.attr('class').match(/icon\-(.*)\-(up|down)/);
			var $icon_down = 'icon-'+$match[1]+'-down';
			var $icon_up = 'icon-'+$match[1]+'-up';
			
			var $body_inner = $body.find('.widget-body-inner')
			if($body_inner.length == 0) {
				$body = $body.wrapInner('<div class="widget-body-inner"></div>').find(':first-child').eq(0);
			} else $body = $body_inner.eq(0);


			var expandSpeed   = 300;
			var collapseSpeed = 200;

			if($box.hasClass('collapsed')) {
				if($icon) $icon.addClass($icon_up).removeClass($icon_down);
				$box.removeClass('collapsed');
				$body.slideUp(0 , function(){$body.slideDown(expandSpeed)});
			}
			else {
				if($icon) $icon.addClass($icon_down).removeClass($icon_up);
				$body.slideUp(collapseSpeed, function(){$box.addClass('collapsed')});
			}
		}
		else if($action == 'close') {
			var closeSpeed = parseInt($this.data('close-speed')) || 300;
			$box.hide(closeSpeed , function(){$box.remove()});
		}
		else if($action == 'reload') {
			$this.blur();

			var $remove = false;
			if($box.css('position') == 'static') {$remove = true; $box.addClass('position-relative');}
			$box.append('<div class="widget-box-layer"><i class="icon-spinner icon-spin icon-2x white"></i></div>');
			setTimeout(function(){
				$box.find('.widget-box-layer').remove();
				if($remove) $box.removeClass('position-relative');
			}, parseInt(Math.random() * 1000 + 1000));
		}
		else if($action == 'settings') {

		}

	});
}



//search box's dropdown autocomplete
ace.enable_search_ahead = function($) {
	ace.variable_US_STATES = ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"],
	$('#nav-search-input').typeahead({
		source: ace.variable_US_STATES,
		updater:function (item) {
			$('#nav-search-input').focus();
			return item;
		}
	});
}



ace.switch_direction = function($) {
	var $body = $(document.body);
	$body
	.toggleClass('rtl')
	//toggle pull-right class on dropdown-menu
	.find('.dropdown-menu:not(.datepicker-dropdown,.colorpicker)').toggleClass('pull-right')
	.end()
	//swap pull-left & pull-right
	.find('.pull-right:not(.dropdown-menu,blockquote,.dropdown-submenu,.profile-skills .pull-right,.control-group .controls > [class*="span"]:first-child)').removeClass('pull-right').addClass('tmp-rtl-pull-right')
	.end()
	.find('.pull-left:not(.dropdown-submenu,.profile-skills .pull-left)').removeClass('pull-left').addClass('pull-right')
	.end()
	.find('.tmp-rtl-pull-right').removeClass('tmp-rtl-pull-right').addClass('pull-left')
	.end()
	
	.find('.chosen-container').toggleClass('chosen-rtl')
	.end()

	.find('.control-group .controls > [class*="span"]:first-child').toggleClass('pull-right')
	.end()
	
	function swap_classes(class1, class2) {
		$body
		 .find('.'+class1).removeClass(class1).addClass('tmp-rtl-'+class1)
		 .end()
		 .find('.'+class2).removeClass(class2).addClass(class1)
		 .end()
		 .find('.tmp-rtl-'+class1).removeClass('tmp-rtl-'+class1).addClass(class2)
	}
	function swap_styles(style1, style2, elements) {
		elements.each(function(){
			var e = $(this);
			var tmp = e.css(style2);
			e.css(style2 , e.css(style1));
			e.css(style1 , tmp);
		});
	}

	swap_classes('align-left', 'align-right');
	swap_classes('arrowed', 'arrowed-right');
	swap_classes('arrowed-in', 'arrowed-in-right');
	swap_classes('messagebar-item-left', 'messagebar-item-right');//for inbox page


	//redraw the traffic pie chart on homepage with a different parameter
	var placeholder = $('#piechart-placeholder');
	if(placeholder.size() > 0) {
		var pos = $(document.body).hasClass('rtl') ? 'nw' : 'ne';//draw on north-west or north-east?
		placeholder.data('draw').call(placeholder.get(0) , placeholder, placeholder.data('chart'), pos);
	}
}
