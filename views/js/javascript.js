//AVOID jQuery WHERE POSSIBLE. IT'S SLOW. (vanilla-js.com)

setInterval ( "savePostBackup()", 10000 ); //every ten seconds
last_content = "";
function savePostBackup() {
	if($("#postcontent").val() != last_content) {
		saveBackup();
	}
	last_content = $("#postcontent").val();
}

$(document).ready(function() {
	$('#imgupload').ajaxForm(function(data) {
		//check if ImageBox is opened, and if it is, refresh the contents
		if(data == "1") {
			displayMessage("image uploaded succesfully");
		}
		else {
			displayError("Image failed to upload");
		}
		console.log(data);
		//implement a failure state.
	});
});
		
function getImageList(off, amo) {
	//loads amount + 1 to check ahead to see if there are more images to load (so it doesn't load a blank page);
	document.getElementById("imageboxtitle").innerHTML = "Choose an image (Page " + ((off / amo) + 1).toString() + ")";
	$.get( "system/ajax/getimages.php", { offset: off, amount: amo+1},  function( data ) {
		lastpage = false;
		newoff = off - amo;
		if(newoff < 0 ) {
			newoff = 0;
		}
		str2 = '<a class="btn btn-default" onclick="getImageList(' + newoff + ', ' + amo + ')">';
		str3 = '<<<';
		str4 = '</a>';
		document.getElementById("imageboxcontentleft").innerHTML = str2 + str3 + str4;
		
		document.getElementById("imageboxcontentcenter").innerHTML = "";
		for(i=0;i<amo;i++) {
			if(eval(data)[i] != undefined) {
				str1 = '<div class="col-xs-6 col-md-2">';
				str2 = '<a href="#" class="thumbnail" style="height:128px; width:128px;" >';
				substr2 = "insertAtCaret('postcontent','![alt text](content/images/" + eval(data)[i] + ")');";
				str3 = '<img src="content/images/' + eval(data)[i] + '" style="max-height:100%; max-width:100%;"  alt="none"; onclick="' + substr2 + '" >';
				str4 = '</a>';
				str5 = '</div>';
				document.getElementById("imageboxcontentcenter").innerHTML += str1 + str2 + str3 + str4 + str5;
			}
		}		
		if(eval(data)[amo] == undefined) {
			newoff = off;
		}
		else {
			newoff = off + amo;
		}
		str2 = '<a class="btn btn-default"  onclick="getImageList(' + newoff + ', ' + amo + ')">';
		str3 = '>>>';
		str4 = '</a>';
		document.getElementById("imageboxcontentright").innerHTML = str2 + str3 + str4;;
	});
	document.getElementById('imagebox').style.display = "block";
}

function hideImageBox() {
	document.getElementById('imagebox').style.display = "none";
}
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
function hideErrorBox() {
	document.getElementById('errorbox').style.display = "none";
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
	$.post( "system/ajax/savebackup.php", { id:$("#postid").val(), content: $("#postcontent").val(), title: $("#posttitle").val(), tags: $("#posttags").val() },  function( data ) {
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

function askDeleteImage(img, id) {
	console.log(id);
	bootbox.dialog({
		message: "Delete image <em>" + img.toString() + "</em>?",
		title: "Confirm delete",
		buttons: {
			main: {
				label: "Cancel",
				className: "btn-default"
			},
			danger: {
				label: "Delete",
				className: "btn-danger",
				callback: function() {
					$.post( "system/ajax/deleteimage.php", { image: img},  function( data ) {
						if(data == 1) {
							document.getElementById('imagecontainer_'+id).style.display = "none";
						}
					});
				}
			}
		}
	});
}
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