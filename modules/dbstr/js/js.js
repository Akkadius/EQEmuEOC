function DoDBStrSearch(){
	$('#search_res').html('<br><br><br><i class="fa fa-spinner fa-spin" style="font-size:80px"></i>');
	$.ajax({ 
		url: "ajax.php?M=dbstr&search_dbstr&search_str=" + encodeURIComponent($('#search_str').val()), 
		context: document.body
	}).done(function(e) {
		$('#search_res').html('<br><h4 class="page-title">Search Results</h4><hr>' + e + '<br><br>');
	}); 
}

function DoDBStrEdit(val, lineid, argnum){
	$.notific8('Performing dbstr_us.txt save...', {  heading: "dbstr_us.txt Editor",  theme: "ruby", life: 1000 });
	$.ajax({ 
		url: "ajax.php?M=dbstr&dbstr_line_edit&replace_text=" + encodeURIComponent(val) + "&line=" + encodeURIComponent(lineid) + "&argnum=" + argnum, 
		context: document.body
	}).done(function(e) {
		// $('#search_res').html('<br><h4 class="page-title">Search Results</h4><hr>' + e);
		$.notific8('Save... Done', {  heading: "dbstr_us.txt Editor",  theme: "ruby", life: 1000 });
	});
}

function DoDBStrDelete(){
	$.notific8('Deleting dbstr_us.txt....', {  heading: "dbstr_us.txt Editor",  theme: "ruby", life: 1000 });
	$.ajax({ 
		url: "ajax.php?M=dbstr&del_dbstr", 
		context: document.body
	}).done(function(e) {
		$.notific8('Done', {  heading: "dbstr_us.txt Editor",  theme: "ruby", life: 1000 });
		location.reload();
	});
}

function DoZipDBStrDownload(){
	$.notific8('Creating Zip Archive...', {  heading: "dbstr_us.txt Editor",  theme: "ruby", life: 1000 });
	$.ajax({ 
		url: "ajax.php?M=dbstr&ZipDownload",  
		context: document.body 
	}).done(function(e) {
		$.notific8('Downloading...', {  heading: "dbstr_us.txt Editor",  theme: "ruby", life: 1000 });
		myVar = setTimeout(function(){ CleanupZip(); }, 10000);
		DownloadFile('l/dbstr_us/dbstr_us.zip'); 
	});
}

function CleanupZip(){
	$.ajax({ 
		url: "ajax.php?M=dbstr&ZipDownloadD", 
		context: document.body
	}).done(function(e) { });
}

var DownloadFile = function DownloadFile(url) {
    var hiddenIFrameID = 'hiddenDownloader',
        iframe = document.getElementById(hiddenIFrameID);
    if (iframe === null) {
        iframe = document.createElement('iframe');
        iframe.id = hiddenIFrameID;
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
    }
    iframe.src = url;
};