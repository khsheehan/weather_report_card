$('.source_wrap').click(function(e){
	var source = $(this).attr('source');
	$('.details_'+source).slideToggle(function(){
		// Nothing
	});
});


$( "#datepicker" ).datepicker();