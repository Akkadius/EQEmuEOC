function ShowZone() {
	var xmlhttp;
	document.getElementById("shownpczone").innerHTML="<b style='color:red;font-size:30px'>LOADING, PLEASE WAIT...</b><hr><i class='fa fa-spinner fa-spin' style='font-size:80px'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("shownpczone").innerHTML=xmlhttp.responseText;
		} 
		$("#shownpczone").find("script").each(function(i) {
			eval($(this).text());
		}); 
	}
	var ZoneSN = document.getElementById("zoneselect").value;
	var InstID = document.getElementById("zinstid").value;
	var NPC = document.getElementById("npcname").value;
	xmlhttp.open("GET","ajax.php?M=NPC&ShowZone=1&Zone=" + ZoneSN + "&Inst=" + InstID + "&NPC=" + encodeURIComponent(NPC), true); 
	xmlhttp.send();
}

function MassFieldEditor(){
	var w = 650;
	var h = 610;
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	window.open("min.php?Mod=NPC&MassFieldEdit=1", "autoscaler", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, height="+h+",width="+w+", toolbar=no, top="+top+", left="+left+"");
} 

function OpenWindow(Url, Title, w, h){
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	window.open(Url, Title, "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, height="+h+",width="+w+", toolbar=no, top="+top+", left="+left+"");
} 

function getRandomInt (min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function SetFieldMassValue(){
	var Field = document.getElementById("npctypesfield").value;
	var Value = document.getElementById("massfieldvalue").value;
	var inputs = opener.document.getElementsByClassName(Field);
	for(i =0; i < inputs.length; i++)  {
		inputs[i].value = Value;
		inputs[i].setAttribute("style","border:1px solid rgba(64,153,255, 1);");
		//alert(inputs[i].id);
		DoFieldUpdateParent(inputs[i].id);
	}
	Notific8("NPC Editor", "Updated " + Field + " to value '" + Value + "'", 3000);
}  

function SetFieldMassValueMinMax(){
	var Field = document.getElementById("npctypesfield").value;
	var Min = parseInt(document.getElementById("minfieldvalue").value);
	var Max = parseInt(document.getElementById("maxfieldvalue").value);
	var inputs = opener.document.getElementsByClassName(Field);
	for(i =0; i < inputs.length; i++)  { 
		inputs[i].value = getRandomInt(Min, Max);
		inputs[i].setAttribute("style","border:1px solid rgba(64,153,255, 1);");
		//alert(inputs[i].id);
		DoFieldUpdateParent(inputs[i].id);
	}
}  

function DoFieldUpdate(val){
	var n=val.split("^");
	var FieldValue = document.getElementById(val).value;
	if(n[0] && n[1]){
		var xmlhttp;
		if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
		else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.open("GET","ajax.php?M=NPC&DoFieldUpdate=1&NPC=" + n[0] + "&Field=" + encodeURIComponent(n[1]) + "&Value=" + encodeURIComponent(FieldValue), true);
		xmlhttp.send();
	}
	Notific8("NPC Editor", "Updated NPCID " + n[0] + " Field '" + n[1] + "' to value '" + val + "'", 3000);  
}

function RaceChange(NPCID, val){
		var xmlhttp;
		if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
		else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				
			}
		}
		document.getElementById("RaceIMG").src="includes/img.php?type=race&id=" + val;
		xmlhttp.open("GET","ajax.php?M=NPC&DoFieldUpdateSingleNPC=1&NPC=" + NPCID + "&Field=race&Value=" + val, true);
		xmlhttp.send();
}

function UpdateSingleNPCEdit(NPCID, Field, val){
		var xmlhttp;
		if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
		else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				
			} 
		}
		if(Field == "armortint_id"){ 
			$(".armortint_red").val(0);
			$(".armortint_green").val(0);
			$(".armortint_blue").val(0); 
			UpdateSingleNPCEdit($("#npc_id").val(), "armortint_red", $(".armortint_red").val());
			UpdateSingleNPCEdit($("#npc_id").val(), "armortint_green", $(".armortint_green").val());
			UpdateSingleNPCEdit($("#npc_id").val(), "armortint_blue", $(".armortint_blue").val()); 
		}
		
		if(Field == "d_meele_texture1"){ document.getElementById("d_meele_texture1").src="includes/img.php?type=weaponimage&id=" + val; }
		if(Field == "d_meele_texture2"){ document.getElementById("d_meele_texture2").src="includes/img.php?type=weaponimage&id=" + val; }
		document.getElementById(NPCID + "^" + Field + "").setAttribute("style","border: 1px solid rgba(64,153,255, 1)");
		xmlhttp.open("GET","ajax.php?M=NPC&DoFieldUpdateSingleNPC=1&NPC=" + NPCID + "&Field=" + Field + "&Value=" + val, true);
		xmlhttp.send();
		Notific8("NPC Editor", "Field '" + Field + "' updated to value '" + val + "'", 3000);
}

function DoFieldUpdateParent(val){ 
	var n=val.split("^");
	var FieldValue = opener.document.getElementById(val).value;
	if(n[0] && n[1]){
		Notific8("NPC Editor", "Field `" + n[1] + "` Updated to Value \'" + FieldValue + "\' on NPC ID: \'" + n[0] + "\'", 3000);
		// opener.document.getElementById("dofieldupdate").innerHTML="Field `" + n[1] + "` Updated to Value \'" + FieldValue + "\' on NPC ID: \'" + n[0] + "\'"; 
		var xmlhttp;
		if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
		else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
		xmlhttp.open("GET","ajax.php?M=NPC&DoFieldUpdate=1&NPC=" + n[0] + "&Field=" + encodeURIComponent(n[1]) + "&Value=" + encodeURIComponent(FieldValue), true);
		xmlhttp.send();
	}
} 

function DoNPCEdit(val){
	DoModal("ajax.php?M=NPC&SingleNPCEdit=" + val);  
} 