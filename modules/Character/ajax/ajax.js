function CharacterTool(Data) {
	var xmlhttp;
	document.getElementById("charactertool").innerHTML="<center><br><br><img src=\'images/loaders/loader8.gif\'></center><br><br>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("charactertool").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?Ajax=Character&CharTool=" + Data, true);
	xmlhttp.send();
}  

function CharSearch(Data) {
	var xmlhttp;
	document.getElementById("charactersearch").innerHTML="<center><br><br><img src=\'images/loaders/loader8.gif\'></center><br><br>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("charactersearch").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?Ajax=Character&CharSearch=" + Data, true);
	xmlhttp.send();
}

function CharSearchMGMT(Data) {
	var xmlhttp;
	document.getElementById("charactersearch").innerHTML="<center><br><br><img src=\'images/loaders/loader8.gif\'></center><br><br>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("charactersearch").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?Ajax=Character&CharSearchMGMT=" + Data, true);
	xmlhttp.send();
}

function DoCharMGMT(Data) {
	var xmlhttp;
	document.getElementById("charactersearch").innerHTML="<center><br><br><img src=\'images/loaders/loader8.gif\'></center><br><br>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("charactersearch").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?Ajax=Character&DoCharMGMT=" + Data, true);
	xmlhttp.send();
}

function DoCharTaskProgShow(Data, TaskID) {
	var xmlhttp;
	document.getElementById("charactersearch").innerHTML="<center><br><br><img src=\'images/loaders/loader8.gif\'></center><br><br>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("charactersearch").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?Ajax=Character&DoCharTaskProgShow=" + Data + "&TaskID=" + TaskID, true);
	xmlhttp.send();
}

function DoGMCommand(Data, Action) {
	var xmlhttp;
	document.getElementById("GMCommand").innerHTML="<center><br><br><img src=\'images/loaders/loader8.gif\'></center><br><br>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("GMCommand").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?Ajax=Character&DoGMCommand=" + Data + "&Action=" + Action, true); 
	xmlhttp.send();
}

function SendTaskUpdate(Data, TaskID, Activity, Count) {
	var xmlhttp;
	
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			//document.getElementById("charactersearch").innerHTML=xmlhttp.responseText;
			document.getElementById("charactersearch").innerHTML="<center><br><br><img src=\'images/loaders/loader8.gif\'></center><br><br>";
			setTimeout(function(){ DoCharTaskProgShow(Data, TaskID); }, 1000); 
		}
	} 
	if(document.getElementById("activity_" + Activity).value){ Count = document.getElementById("activity_" + Activity).value; }
	xmlhttp.open("GET","ajax.php?Ajax=Character&SendTaskUpdate=" + Data + "&TaskID=" + TaskID + "&ActivityID=" + Activity + "&Count=" + Count, true);
	xmlhttp.send();
	
}

function CopyCharacter(){
	if(document.getElementById("origchar").value == "Origin Character"){
		document.getElementById("copycharresults").innerHTML="There needs to be an Origin Character specified";
		return;
	}
	if(document.getElementById("destaccount").value == "Destination Account"){
		document.getElementById("copycharresults").innerHTML="There needs to be a Destination Account specified";
		return;
	}
	var CharCopy = document.getElementById("origchar").value;
	var DestAcc = document.getElementById("destaccount").value;
	
	var xmlhttp;
	document.getElementById("copycharresults").innerHTML="<center><br><br><img src=\'images/loaders/loader8.gif\'></center><br><br>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("copycharresults").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?Ajax=Character&CopyChar=1&CharToCopy=" + CharCopy + "&DestAcc=" + DestAcc, true);
	xmlhttp.send();
}