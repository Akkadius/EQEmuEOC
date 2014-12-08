function ZoneToolZoneSelect(Data) {
	var xmlhttp;
	document.getElementById("ZoneSelect").innerHTML="<i class='fa fa-spinner fa-spin' style='font-size:100px;padding:50px;color:#666'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("ZoneSelect").innerHTML=xmlhttp.responseText; 
		}
	}
	xmlhttp.open("GET","ajax.php?M=ZT&ZoneID=" + Data + "&Selector=1", true);
	xmlhttp.send();
} 

function ZoneToolCopyZoneSelect(ZoneID, Data) {
	var xmlhttp; 
	document.getElementById("ZoneToolCopyZoneSelectOP").innerHTML="<i class='fa fa-spinner fa-spin' style='font-size:80px;padding:50px;color:#666'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("ZoneToolCopyZoneSelectOP").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?M=ZT&ZoneID=" + ZoneID + "&CopyTool=" + Data, true);
	xmlhttp.send();
} 

function CopyZoneVersion(ZoneID, Source, Dest, NPCDATA, NPCGRIDS) {
	var xmlhttp;
	document.getElementById("ZoneToolCopyZoneSelectOP2").innerHTML="<i class='fa fa-spinner fa-spin' style='font-size:80px;padding:50px;color:#666'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("ZoneToolCopyZoneSelectOP2").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?M=ZT&ZoneID=" + ZoneID + "&Source=" + Source + "&Dest=" + Dest + "&CopyToolSubmit=1" + "&NPCDATA=" + NPCDATA + "&NPCGRIDS=" + NPCGRIDS, true);
	xmlhttp.send();
} 

function DeleteZoneVersion(ZoneID, Version) {
	var xmlhttp;
	document.getElementById("ZoneToolDeleteData").innerHTML="<i class='fa fa-spinner fa-spin' style='font-size:80px;padding:50px;color:#666'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("ZoneToolDeleteData").innerHTML=xmlhttp.responseText;
		}
	}

	xmlhttp.open("GET","ajax.php?M=ZT&ZoneID=" + ZoneID + "&VersionToDelete=" + Version, true);
	xmlhttp.send();
} 

function ToggleOtherOptions(Data) {
	var xmlhttp;
	document.getElementById("NPCGRIDS").innerHTML="<i class='fa fa-spinner fa-spin' style='font-size:80px;padding:50px;color:#666'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("NPCGRIDS").innerHTML=xmlhttp.responseText;
		}
	}

	xmlhttp.open("GET","ajax.php?M=ZT&NPCGRIDSSHOW=" + Data, true);
	xmlhttp.send();
} 

function DeleteZoneVersionSubmit(ZoneID, Version, DeleteType, Objects, Doors) {
	var xmlhttp;
	document.getElementById("ZoneToolDeleteData").innerHTML="<i class='fa fa-spinner fa-spin' style='font-size:80px;padding:50px;color:#666'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("ZoneToolDeleteData").innerHTML=xmlhttp.responseText;
		} 
	}
	xmlhttp.open("GET","ajax.php?M=ZT&ZoneID=" + ZoneID + "&VersionToDelete=" + Version + "&Submit=1" + "&DeleteType=" + DeleteType + "&ObjectsDelete=" + Objects + "&DoorsDelete=" + Doors, true);
	xmlhttp.send();
} 


function ZoneToolZoneSelect2nd(Data) {
	var xmlhttp;
	document.getElementById("ZoneSelect2").innerHTML="<i class='fa fa-spinner fa-spin' style='font-size:80px;padding:50px;color:#666'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("ZoneSelect2").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?M=ZT&ZoneID=" + Data + "&ImportSelector=1", true);
	xmlhttp.send();
} 

function ListZoneRemote(ZoneID, Version) {
	var xmlhttp;
	document.getElementById("ListZoneRemote").innerHTML="<i class='fa fa-spinner fa-spin' style='font-size:80px;padding:50px;color:#666'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("ListZoneRemote").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?M=ZT&ZoneID=" + ZoneID + "&Version=" + Version + "&ListZone=1", true);
	xmlhttp.send();
} 

function ListZoneLocal(ZoneID, Version) {
	var xmlhttp;
	document.getElementById("ListZoneLocal").innerHTML="<i class='fa fa-spinner fa-spin' style='font-size:80px;padding:50px;color:#666'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("ListZoneLocal").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","ajax.php?M=ZT&ZoneID=" + ZoneID + "&Version=" + Version + "&ListZoneLocal=1", true);
	xmlhttp.send();
} 

function CopyZoneVersionExtToLoc(ZoneID, Source, Dest, Doors, Objects) {
	var xmlhttp;
	document.getElementById("CopyZoneVersionExtToLoc").innerHTML="<i class='fa fa-spinner fa-spin' style='font-size:80px;padding:50px;color:#666'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("CopyZoneVersionExtToLoc").innerHTML=xmlhttp.responseText;
		} 
	}
	xmlhttp.open("GET","ajax.php?M=ZT&ZoneID=" + ZoneID + "&Source=" + Source + "&Dest=" + Dest + "&ImportTool=1" + "&Doors=" + Doors + "&Objects=" + Objects, true);
	xmlhttp.send(); 
} 

function DoDBSwitch2nd(db, zid){ 
	if(db == 0){ return; }
	$.ajax({
		url: "ajax.php?M=DBAuth&DoDBSwitch2nd=" + encodeURIComponent(db),
		context: document.body
	}).done(function(e) {
		if(e.indexOf("Login Success") > -1){
			// return;
			// window.location = 'index.php'; 
			$.notific8("2nd Database connection switched to: " + db, {
				heading: "EOC",
				theme: "ruby",
				life: 3000
			});
			ZoneToolZoneSelect2nd(zid);
		} 
	});
}
