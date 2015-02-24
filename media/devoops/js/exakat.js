//
//    Main script of DevOOPS v1.0 Bootstrap Theme
//
"use strict";

if(/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())){
   alert('Sorry, this report is not compatible with Chrome or Opera. Try Firefox or Safari.');
}

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


