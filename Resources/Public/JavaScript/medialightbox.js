
$(document).ready(function() {
	var ie_old = false;

	if(navigator.appVersion.indexOf("MSIE 7.")!=-1) ie_old = true;
	if(navigator.appVersion.indexOf("MSIE 8.")!=-1) ie_old = true;

	if (ie_old) {

		$(".medialightbox").fancybox({
			maxWidth	: 800,
			maxHeight	: 600,
			fitToView	: false,
			autoSize	: false,
			width: 600,
			height: 400
		});

	} else {
		$(".medialightbox").fancybox({
			maxWidth	: 800,
			maxHeight	: 600,
			fitToView	: true,
			autoSize	: false
		});
	}

});