// ******* init barba with no transition effect *************************************************************************
$(document).ready(function(){
	Barba.Pjax.start();
	
	var HideShowTransition = Barba.BaseTransition.extend({
		start: function() {
			this.newContainerLoading.then(this.finish.bind(this));
		},
	
		finish: function() {
			this.done();
		}
	});
	
	Barba.Pjax.getTransition = function() {
		window.scrollTo(0, 0);
		return HideShowTransition;
	};
});