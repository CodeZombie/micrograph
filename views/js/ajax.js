//AVOID jQuery WHERE POSSIBLE. IT'S SLOW. (vanilla-js.com)
//FIND A WAY TO MAKE AJAX CALLS W/O JQUERY. MAYBE TRY A LIGHTER-WEIGHT LIBRARY (modify microajax.js?)

$(document).ready(function() {
	$('#imgupload').ajaxForm(function() {
		displayMessage("image uploaded succesfully");
	});
});
		
function displayError(errormessage) {
	document.getElementById('errorbox').style.display = "block";
	document.getElementById('errorboxcontent').innerHTML = errormessage;
	scroll(0,0);
}

function displayMessage(message) {
	document.getElementById('messagebox').style.display = "block";
	document.getElementById('messageboxcontent').innerHTML = message;
	scroll(0,0);
}
function hideMessageBox() {
	document.getElementById('messagebox').style.display = "none";
}

function clickPreviewTab() {
	document.getElementById('markuptab').classList.remove('active');
	document.getElementById('previewtab').classList.add('active');
	$.post( "system/ajax/markdown.php", { markdown: document.getElementById('postcontent').value },  function( data ) {
		document.getElementById('previewcontent').innerHTML = nl2br(data);
	});
	document.getElementById('postcontent').style.display = "none";
	document.getElementById('previewcontent').style.display = "block";
}

function clickMarkupTab() {
	document.getElementById('markuptab').classList.add('active');
	document.getElementById('previewtab').classList.remove('active');
	document.getElementById('postcontent').style.display = "block";
	document.getElementById('previewcontent').style.display = "none";
}

function saveBackup() {
	$.post( "system/ajax/savebackup.php", { content: $("#postcontent").val(), title: $("#posttitle").val() },  function( data ) {
		alert("saved!");
	});
}

function nl2br(text){
	text = escape(text);
	var re_nlchar;
	if(text.indexOf('%0D%0A') > -1){
		re_nlchar = /%0D%0A/g ;
	}else if(text.indexOf('%0A') > -1){
		re_nlchar = /%0A/g ;
	}else if(text.indexOf('%0D') > -1){
		re_nlchar = /%0D/g ;
	}
	return unescape( text.replace(re_nlchar,'<br />') );
}

Mousetrap.bindGlobal('ctrl+s', function(e) {
    bootbox.alert('Draft saved. (not really)');
	return false;
});

function insertAtCaret(areaId,text) {
    var txtarea = document.getElementById(areaId);
    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
    	"ff" : (document.selection ? "ie" : false ) );
    if (br == "ie") { 
    	txtarea.focus();
    	var range = document.selection.createRange();
    	range.moveStart ('character', -txtarea.value.length);
    	strPos = range.text.length;
    }
    else if (br == "ff") strPos = txtarea.selectionStart;

    var front = (txtarea.value).substring(0,strPos);  
    var back = (txtarea.value).substring(strPos,txtarea.value.length); 
    txtarea.value=front+text+back;
    strPos = strPos + text.length;
    if (br == "ie") { 
    	txtarea.focus();
    	var range = document.selection.createRange();
    	range.moveStart ('character', -txtarea.value.length);
    	range.moveStart ('character', strPos);
    	range.moveEnd ('character', 0);
    	range.select();
    }
    else if (br == "ff") {
    	txtarea.selectionStart = strPos;
    	txtarea.selectionEnd = strPos;
    	txtarea.focus();
    }
    txtarea.scrollTop = scrollPos;
}