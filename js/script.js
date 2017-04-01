//******** Add pdf and other filetypes to barba's exclude list. **************************
Barba.Pjax.originalPreventCheck = Barba.Pjax.preventCheck;

Barba.Pjax.preventCheck = function(evt, element) {
  if (!Barba.Pjax.originalPreventCheck(evt, element)) {
    return false;
  }

  if (/.pdf/.test(element.href.toLowerCase())) {
    return false;
  }
	
	if (/wp-admin/.test(element.href.toLowerCase())) {
		return false;
	}

  return true;
};

// ******** BEGIN PAGE LOAD / TRANSITION FUNCTIONS ****************************************
Barba.Dispatcher.on('newPageReady', function(currentStatus, prevStatus){ // instead of $(document).ready(function(){ // because we are using barba.js
	//******** Move homepage carousel outside of main if option is set to full width.
	$('#carousel-home-top-full.full-width').remove();
	var $carousel = $('#carousel-home-top-full');
	$('header.header').after($carousel);
	$carousel.css('display', 'block').addClass('full-width');
	
	//******** Close navbar on mobile devices.
	$('.navbar-collapse').removeClass('in');
	
	//******** Unset nav active link and set new active + ancestors.
	$('.navbar-nav .active').removeClass('active');
	$('.navbar-nav .current-menu-ancestor').removeClass('current-menu-ancestor');
	$('.navbar-nav a[href="' + currentStatus['url'] + '"]').closest('li').addClass('active');
	$('.navbar-nav li.active').closest('ul').closest('li').addClass('current-menu-ancestor');
	jQuery.SmartMenus.Bootstrap.init();
	
	//******** Init carousel.
	$('.carousel').carousel();
	
	//******** Init popovers and tooltips.
	$('[data-toggle="popover"]').popover();
	$('[data-toggle="tooltip"]').tooltip();
});

Barba.Dispatcher.on('transitionCompleted', function(currentStatus, prevStatus){ // instead of $(document).ready(function(){ // because we are using barba.js
	//******** Init image lazy loader.
	$('img.lazy').lazyload();
	
	//******** Find iframes that contain youtube videos and wrap them in bootstrap elements.
	$('iframe[src*="youtube.com/"], iframe[src*="youtu.be/"]').each(function(){
		$(this).addClass('embed-responsive-item');
		if($(this).closest('div.embed-responsive').length){
			$(this).closest('div.embed-responsive').addClass('embed-responsive-16by9');
		}else{
			$(this).wrap('<div class="embed-responsive embed-responsive-16by9"></div>');
		}
	});
	
	$//********* Add bootstrap table class to all tables in content
	$('main table').addClass('table').addClass('table-striped').addClass('table-condensed');
	
	//******** Barba does not update page title and other metadata. We have to inject it.
	var page_title = $( '.xhr-container' ).data( 'page-title' );
	$( 'title' ).text( page_title );
	$( 'meta[property="og\\:title"]' ).attr( 'content', page_title );
	$( 'meta[name="twitter\\:title"]' ).attr( 'content', page_title );
	$( 'meta[property="og\\:url"]' ).attr( 'content', currentStatus['url'] );
	
	//******** Make sure we track pageview after updating the title.
	if( typeof googleAnalyze == 'function' ) {
		googleAnalyze( location.pathname );
	}
});
// ******** END PAGE LOAD / TRANSITION FUNCTIONS ******************************************

//******** Init lightbox galleries.
$('body').on('click', '.gallery a', function(e){
	// insert the gallery container with all the required elements
	if(!$('#blueimp-gallery').length){
		$('body').append(
			'<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">'+
				'<div class="slides"></div>'+
				'<h3 class="title"></h3>'+
				'<a class="prev">‹</a>'+
				'<a class="next">›</a>'+
				'<a class="close">×</a>'+
				'<a class="play-pause"></a>'+
			'</div>');
	}
	
	// launch the requested gallery
	e = e || window.e;
	t = e.target || e.srcElement;
	k = t.src ? t.parentNode : t;
	o = {
		index: k,
		event: e,
		fullScreen: $('.xhr-container').data('gallery-fullscreen'),
	};
	s = $(this).closest('.gallery').find('a');
	blueimp.Gallery(s, o);
})

//******** Prevent links whose href="#" from doing anything.
$('body').on('click', 'a[href="#"]', function(e){
	e.preventDefault();
});

//*********** call modal window with source code of current element ***********************
/*var $button = $('body').on('click', '<div id="source-button" class="btn btn-primary btn-xs">&lt; &gt;</div>', function(){
	var html = $(this).parent().html();
	html = cleanSource(html);
	$("#source-modal pre").text(html);
	$("#source-modal").modal();
});*/

/*var $button = $("<div id='source-button' class='btn btn-primary btn-xs'>&lt; &gt;</div>").click(function(){
	var html = $(this).parent().html();
	html = cleanSource(html);
	$("#source-modal pre").text(html);
	$("#source-modal").modal();
});*/

//************ show view source links ******************************
/*Barba.Dispatcher.on('transitionCompleted', function(currentStatus, prevStatus){
	$('body').on({
		mouseenter: function(){
			$(this).append($button);
			$button.show();
		},
		mouseleave: function(){
			$button.hide();
		}
	}, '.bs-component');
});*/

//********** clean source *************************************
function cleanSource(html) {
	html = html.replace(/×/g, "&times;")
						 .replace(/«/g, "&laquo;")
						 .replace(/»/g, "&raquo;")
						 .replace(/←/g, "&larr;")
						 .replace(/→/g, "&rarr;");

	var lines = html.split(/\n/);

	lines.shift();
	lines.splice(-1, 1);

	var indentSize = lines[0].length - lines[0].trim().length,
			re = new RegExp(" {" + indentSize + "}");

	lines = lines.map(function(line){
		if (line.match(re)) {
			line = line.substring(indentSize);
		}

		return line;
	});

	lines = lines.join("\n");

	return lines;
}