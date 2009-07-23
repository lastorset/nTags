/* JavaScripts for nTags. */
registerNS("nTags");

// Helper function to search an array
nTags.searchArray = function(haystack, needle) {
	for (i = 0; i < haystack.length; i++) {
		if (haystack[i] == needle)
			return i;
	}
	return -1;
};

// Helper function to print an array, for debugging purposes
function toString(obj, prefix) {
	if (prefix == undefined) 
		prefix = "";

	var str = typeof(obj) +" ";
	if(obj.constructor == Function) {
	} else if(obj.constructor == Array || obj.constructor == Object) {
		str += "{\n";
		for(var p in obj) {
			str += prefix +"\t["+ p +"]: "+ toString(obj[p], prefix +"\t") +"\n";
		}
		str += prefix +"}";
	} else {
		str += "\""+ obj +"\"";
	}
	return str;
}
