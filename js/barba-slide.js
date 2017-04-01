// ******* init barba with slide transition effect *************************************************************************
$(document).ready(function(){
	Barba.Pjax.start();
	
	var SlideTransition = Barba.BaseTransition.extend({
		start: function() {
			Promise
				.all([this.newContainerLoading])
				.then(this.slideIn.bind(this));
		},

		slideIn: function() {
			var _this = this;
			var $el = $(this.newContainer);

			$(this.oldContainer).hide();

			$el.css({
				visibility : 'visible',
				position : 'relative',
				left : '100%'
			});

			$el.animate({
				left : '0%'
			}, 300, function() {
				_this.done();
			});
		}
	});

	Barba.Pjax.getTransition = function() {
		window.scrollTo(0, 0);
		return SlideTransition;
	};
});