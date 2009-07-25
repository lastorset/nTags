registerNS("nTags.filter");
onclick="nTags.filter.activate('{$node.url}', $('#nTagsFilterText').attr('value'))" 

nTags.filter.activate = function() {
	var url = $("#nTagsNodeURL").val();
	var tags = $("#nTagsFilterText").val();
	if (tags == "") {
		window.location = url;
	} else {
		window.location = url + "/(tags)/"+ tags;
	}
};

nTags.filter.clear = function() {
	var url = $("#nTagsNodeURL").val();
	alert(url);
	window.location = url;
};

$( function() {
	$("#nTagsFilterSubmit").click( function(e) { e.preventDefault(); nTags.filter.activate(); } );
	$("#nTagsClearFilter").click( function(e) { e.preventDefault(); nTags.filter.clear(); } );
});
