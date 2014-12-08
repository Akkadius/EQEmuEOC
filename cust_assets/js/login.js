function VerifyDBAuth() {
	var xmlhttp;
	document.getElementById("DBAUTH").innerHTML="<center><i class='fa fa-spinner fa-spin' style='font-size:80px'>";
	if (window.XMLHttpRequest) { xmlhttp=new XMLHttpRequest(); }
	else {  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("DBAUTH").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", "ajax.php?M=DBAuth&VerifyDBAuth&ip=" + encodeURIComponent($('#db_ip').val()) + "&dbname=" + encodeURIComponent($('#db_name').val()) + "&dbuser=" + encodeURIComponent($('#db_user').val()) + "&dbpass=" + encodeURIComponent($('#db_pass').val()), true);
	xmlhttp.send();
} 

function DoDBLogin(){ 
	$.ajax({
		url: "ajax.php?M=DBAuth&DoDBLogin&ip=" + encodeURIComponent($('#db_ip').val()) + "&dbname=" + encodeURIComponent($('#db_name').val()) + "&dbuser=" + encodeURIComponent($('#db_user').val()) + "&dbpass=" + encodeURIComponent($('#db_pass').val()),
		context: document.body
	}).done(function(e) {
		if(e.indexOf("Login Success") > -1){
			window.location = 'index.php';
		} 
	});
}

function stopRKey(evt) { 
  var evt = (evt) ? evt : ((event) ? event : null); 
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
} 

document.onkeypress = stopRKey; 