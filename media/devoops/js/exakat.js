//
//    Main script of DevOOPS v1.0 Bootstrap Theme
//
"use strict";

$(document).ready(function () {
	$('body').on('click', '.exakat-link', function (e) {
		e.preventDefault();
		if ($(this).hasClass('add-full')) {
			$('#content').addClass('full-content');
		}
		else {
			$('#content').removeClass('full-content');
		}
		var url = $(this).attr('href');
		window.location.hash = url;
		LoadAjaxContent(url);
	});
});


