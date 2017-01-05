$("a").click(function(e){
	e.preventDefault();
	var url = $(this).attr("ref");
	$.ajax({
	  url: url,
	  dataType:'text'
	}).done(function(d) {
	  $(".main").html(d);
	});
})