

var server = window.location.hostname;
jQuery('a[href^="http://"]:not([href*="'+server+'"]), a[href^="https://"]:not([href*="'+server+'"]), a[href^="//"]:not([href*="'+server+'"])').attr('target', '_blank').addClass("track-uri-event");

jQuery('.track-uri-event').click(function() {

	if (jQuery(this).data('uri-category') === undefined) {
		jQuery(this).data('uri-category','outbound-link');
	}

	var event_category = jQuery(this).data('uri-category');
	var url = jQuery(this).attr('href');
	gtag('event', 'click', {
		'event_category': event_category,
		'event_label': url,
		'transport_type': 'beacon'
	});

});
