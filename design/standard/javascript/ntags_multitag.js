registerNS("nTags.multi");

nTags.multi.saveTags = function() {
	function dirtyObject(version, attr, tr, labels) {
		this.version = version;
		this.attr = attr;
		this.tr = tr;
		this.labels = labels;
	};
	var dirtyObjects = new Array();
	// Traverse all tags and determine whether they need storing
	$(".nTagsEdit tr td.tags").each( function() {
		// Find version and attribute
		var version = $(this).data("version");
		var attrID = $(this).attr("id").replace("nTags_", "");
		// Store references to labels
		var labels = new Array();
		var dirty = false;
		// Walk through labels looking for dirty ones
		$(this).find("label").each( function() {
			var label = $(this);
			// If it's checked, store it
			if(label.hasClass("checked")) {
				labels.push($.trim(label.text()));
			}
			// Checked but not saved => a new label
			if(label.hasClass("checked") && !label.hasClass("saved")) {
				label.addClass("new");
				dirty = true;
			}
			// Saved but not checked => a deleted label
			if(!label.hasClass("checked") && label.hasClass("saved") ) {
				label.addClass("delete");
				dirty = true;
			}
			// Not saved and not checked => a new label that was not added
			if(!label.hasClass("checked") && !label.hasClass("saved") ) {
				label.remove();
			}
		});
		// If there are new or deleted labels, mark this row as dirty.
		if (dirty) {
			$(this).append("<img class='saving' src='/extension/ntags/design/standard/images/ajax-loader-trans.gif' alt='Saving...'/>");
			dirtyObjects.push(new dirtyObject(version, attrID, $(this).parent(), labels));
		}
	});
	// Mark dirty rows visually while saving
	for(i = 0; i < dirtyObjects.length; i++) {
		var dirty = dirtyObjects[i];
		var options = {attrID: dirty.attr, version: dirty.version, index: i, "tags[]": dirty.labels, removeAll: (dirty.labels.length == 0 ? true : false)};
		$.post("/ntags/multitag_ajax", options, function(response) { 
			if (response.substr(0,7) == "success") {
				var index = response.substr(9);
				dirtyObjects[index].tr.find("td.tags label.new").each( function() {
					$(this).removeClass("new");
					$(this).addClass("saved");
					});
				dirtyObjects[index].tr.find("td.tags label.delete").remove();
				dirtyObjects[index].tr.find("img.saving").remove();
			} else {
				alert(response);
				dirtyObjects[index].tr.find("img.saving").remove();
			}
		});
	}
};

nTags.multi.addNewTag = function() {
	// Split into multiple tags by commas
	var newTags=$("#nTagsNewTag").attr("value").split(",");

	// Reverse because they are prepended.
	for(var i = newTags.length - 1; i >= 0; i--) {
		// For each new tag
		// Trim
		var newTag=jQuery.trim(newTags[i]);
		// Create short version for ids
		var newTagShort=newTag.replace(" ", "", "g").toLowerCase();

		var checked=$("#nTagsNewTagChecked").attr("checked");
		if (newTag != "") {
			$(".nTagsEdit td.tags").each( function() {
				var newTagId = this.id +"_"+ newTagShort;
				// Prevent duplicate tags.
				if($(this).find("input[id='"+ newTagId +"']").size() == 0) {
					// Prepend to make it easier to select/unselect new tags
					$(this).prepend('<label '+ (checked ? 'class="checked"' : '') +' for="'+ newTagId +'">'+ newTag +' <input type="checkbox"'+ (checked ? ' checked="checked"' : '') +' id="'+ newTagId +'" name="'+ newTagId +'" /> </label> ');
				}
			});
		};
		$("#nTagsNewTag").attr("value", "");
	}
	return false;
};

$(document).ready(function() {
	$("#nTagsChildren .tags input[type='checkbox']").live("change", function() {
		$(this).parent().toggleClass("checked", $(this).attr("checked"));
	}).change();
	$("#nTagsEditChildren").removeAttr("disabled");
	$("#nTagsSave").hide();
	$("#nTagsAddTagDiv").hide();
	$("input#nTagsEditChildren").click( function() {
		$("#nTagsChildren .tags input[type='checkbox']").removeAttr("disabled");
		$("#nTagsChildren").addClass("nTagsEdit");
		$("#nTagsChildren").removeClass("nTagsView");
		$("#nTagsSave").fadeIn();
		$("#nTagsEditChildren").attr("disabled", "disabled");
		$("#nTagsAddTagDiv").slideDown();
		return false;
	});
	$("input#nTagsSave").click( function(e) {
		e.preventDefault();
		nTags.multi.saveTags();
		$("#nTagsSave").fadeOut();
		$("#nTagsChildren").addClass("nTagsView");
		$("#nTagsChildren").removeClass("nTagsEdit");
		$("#nTagsEditChildren").removeAttr("disabled");
		$("#nTagsAddTagDiv").slideUp();
		$("#nTagsChildren .tags input[type='checkbox']").attr("disabled", "disabled");
	});
	$("input#nTagsAddTag").click(nTags.multi.addNewTag);

	$("input#nTagsNewTag").keyup( function(e) {
		if (this.value != "") {
			$("#nTagsAddTag").removeAttr("disabled");
		} else {
			$("#nTagsAddTag").attr("disabled", "disabled");
		}
	});
});

$(function () {
	$("input#nTagsEditChildren").removeAttr("disabled");
});
