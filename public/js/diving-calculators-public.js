(function( $ ) {
	'use strict';

	$(document).ready(function($) {

		$('.diving-calculators-form').submit(function(e){
			e.preventDefault();
			var data = $(this).serializeArray();
			data.unshift({name: "water", value: $('.diving_calculator_options input[name="diving_calc_water"]:checked').val()});
			data.unshift({name: "system", value: $('.diving_calculator_options input[name="diving_calc_system"]:checked').val()});
			var type = data[2].value;
			$.ajax({ 
				 data: {
					 action: 'form_calc', 
					 formData: data},
				 type: 'post',
				 url: diving_calculators_ajax_object.ajax_url,
				 success: function(data) {
					  $('#diving-calculators-'+type+'-result').text(data);
				}
			});
		
		})
	})

})(jQuery);
	
window.addEventListener("load", function() {
	var coll = document.getElementsByClassName("dc-collapsible");
	var i;
	for (i = 0; i < coll.length; i++) {
		coll[i].addEventListener("click", function() {
			this.classList.toggle("dc-active");
			var content = this.nextElementSibling;
			if (content.style.maxHeight){
			content.style.maxHeight = null;
			} else {
			content.style.maxHeight = (content.scrollHeight+80) + "px";
			} 
		});
	}
});