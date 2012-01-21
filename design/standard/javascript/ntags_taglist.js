registerNS("nTags.taglist");

nTags.taglist.saveSort = function(callback) {
	$("#sortInstructions").hide();
	$("#saveStatus").show();
	$("#saveResult").slideUp();
	var tags = $("td label.tag").get().map( function(elem, index) {
		return $(elem).html();
	});
	$.ez('nTagsServer::saveTaglistSort', {SaveSortButton: true, tags: tags}, function (response) {
		$("#saveStatus").hide();
		if(response.error_text) {
			$("#saveResult .message").html(response.error_text);
			$("#saveResult").slideDown();
			$("#sortInstructions").show();
		} else {
			callback( function() {
				$("#sortInstructions").show();
				$("#saveStatus").hide();
			});
		}
	});
};

nTags.taglist.sortEnable = function() {
		$("#sortControls").slideDown();
		$("#taglist tbody").sortable({
			axis: "y",
			cursor: "move",
			revert: true
		});
		$("#taglist tbody").disableSelection();
		$("#SortButton").attr("disabled", "disabled");
		$("#RemoveButton").attr("disabled", "disabled");
		$("#taglist tbody input").slideUp("normal", function() { $("#taglist tbody .sortableArrow").fadeIn("normal") });
};

nTags.taglist.sortDisable = function() {
		$("#taglist tbody .sortableArrow").fadeOut("fast", function() { $("#taglist tbody input").slideDown("slow") });
		$("#taglist tbody").sortable('destroy');
		$("#sortControls").slideUp( function() { $("#saveResult").hide() } );
		$("#SortButton").removeAttr("disabled");
		$("#RemoveButton").removeAttr("disabled");
};

$(document).ready( function() {
	nTags.taglist.sortDisable();
	$("#DoneSortingButton").click( function(e) {
		e.preventDefault();
		nTags.taglist.sortDisable();
	});
	$("#SaveSortButton").click( function(e) {
		e.preventDefault();
		$("#taglist tbody .sortableArrow").fadeOut("normal");
		nTags.taglist.saveSort( function(cb) { 
			nTags.taglist.sortDisable();
		});
	});
	$("#SortButton").click( function(e) {
		e.preventDefault();
		nTags.taglist.sortEnable();
	});
	$("#taglist tbody .sortableArrow").hide();
});
