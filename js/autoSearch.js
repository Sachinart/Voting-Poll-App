function bindAutoSearch(searchInputId, divListId, column, table) {
	var searchInput = $("#" + searchInputId);
	var divList = $("#" + divListId);
	
	searchInput.keyup(function(){
		var value = $(this).val();
		var value = value.replace("'", "\\'");
		$.ajax({	
		type: "POST",
		url: "action/auto-search.php",
		data: {
			keyword: value,
			column: column,
			searchInputId: searchInputId,
			divListId: divListId,
			table: table			
		},
		beforeSend: function(){
			searchInput.css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
		},
		success: function(data){
			// alert(data);
			divList.html(data);
			divList.show();
			searchInput.css("background","#FFF");
		}
		});
	});
}
	
function selectVal(searchInputId, divListId, val) {
	var searchInput = $("#" + searchInputId);
	var divList = $("#" + divListId);
	
	searchInput.val(val);
	divList.hide();
}