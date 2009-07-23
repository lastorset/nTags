registerNS("nTags.edit");

nTags.edit.scanCheckboxes = function(attr_id) {
	$("#"+ attr_id + "_maintags").find("label").each( function() {
		var labelFor = $(this).attr("for");
		if($("#"+ labelFor).attr("checked")) {
			var tag = $(this).html();
			nTags.edit.addCheckedTag(tag);
		};
	});
};

nTags.edit.checkedTags = new Array();

// When a tag is checked/unchecked, this function figures out what happened and calls regeneration of the hidden tag attribute.
nTags.edit.setTag = function (tag, checkbox, freeTagInputId, tagInputId) {
	if (checkbox.checked == true) {
		nTags.edit.addCheckedTag(tag);
	} else if (checkbox.checked == false) {
		nTags.edit.removeCheckedTag(tag);
	}
	nTags.edit.genAttribute(freeTagInputId, tagInputId);
};

// When a tag is checked, add it to the array.
nTags.edit.addCheckedTag = function(tag) {
	if (nTags.searchArray(nTags.edit.checkedTags, tag) == -1) {
		nTags.edit.checkedTags[nTags.edit.checkedTags.length] = tag;
	}
};

// When a tag is unchecked, remove it from the array.
nTags.edit.removeCheckedTag = function(tag) {
	var tagIndex = nTags.searchArray(nTags.edit.checkedTags, tag);
	if (tagIndex != -1) {
		nTags.edit.checkedTags.splice(tagIndex, 1);
	}
};

// Regenerate hidden tag attribute based on changes in visible controls. The checked tags are inserted first, then the free tags.
// tagInput is the hidden tag attribute control.
nTags.edit.genAttribute = function(freeTagInputId, tagInputId) {
	var freeTagInput = document.getElementById(freeTagInputId);
	var tagInput = document.getElementById(tagInputId);

	// Clear

	tagInput.value = "";
	// Insert checked predefined tags
	for(i = 0; i < nTags.edit.checkedTags.length; i++) {
		var tag = nTags.edit.checkedTags[i];
		tagInput.value += tag + ", ";
	}

	// Insert free tags
	tagInput.value += freeTagInput.value;
};
