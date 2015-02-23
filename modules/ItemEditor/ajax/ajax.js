function GetItemSearchForm(a) {
    var b;
    document.getElementById("DataSettings").innerHTML = "<center><br><br><i class='fa fa-spinner fa-spin' style='font-size:80px'></center><br><br>";
    if (window.XMLHttpRequest) b = new XMLHttpRequest(); else b = new ActiveXObject("Microsoft.XMLHTTP");
    b.onreadystatechange = function() {
        if (4 == b.readyState && 200 == b.status) document.getElementById("DataSettings").innerHTML = b.responseText;
    };
    b.open("GET", "../itemsinc.php?ShowSearch=1", true);
    b.send();
}

function GetIconResult(a, b) {
    $('#IconResult').html('<i class="fa fa-spinner fa-spin" style="font-size:180px;color:#666;position:absolute;left:50%;top:50%"></i>');
    $.ajax({
        url: "ajax.php?M=Items&previcon=1&IconSearch=" + a + "&Type=" + b,
        context: document.body
    }).done(function(e) {
        /* Update Data Table as well */
        $('#IconResult').html(e).fadeIn();
        $('#IconResult').attr('lootdrop_loaded', loot_drop);
    });
}

function GetITFileResult(a) {
    var b;
    document.getElementById("ITFileResult").innerHTML = "<center><br><br><i class='fa fa-spinner fa-spin' style='font-size:80px'></center><br><br>";
    if (window.XMLHttpRequest) b = new XMLHttpRequest(); else b = new ActiveXObject("Microsoft.XMLHTTP");
    b.onreadystatechange = function() {
        if (4 == b.readyState && 200 == b.status) document.getElementById("ITFileResult").innerHTML = b.responseText;
    };
    b.open("GET", "min.php?Mod=IE&prevITfile=1&WeaponType=" + a, true);
    b.send();
}

function EnlargeWeaponImage(a, b) {
    a.src = b;
    a.style.height = "600px";
    a.style.width = "400px";
}

function GetItemEditForm(a) {
    var b;
    document.getElementById("ItemEditForm").innerHTML = "<center><br><br><i class='fa fa-spinner fa-spin' style='font-size:80px'></center><br><br>";
    if (window.XMLHttpRequest) b = new XMLHttpRequest(); else b = new ActiveXObject("Microsoft.XMLHTTP");
    b.onreadystatechange = function() {
        if (4 == b.readyState && 200 == b.status) document.getElementById("ItemEditForm").innerHTML = b.responseText;
    };
    b.open("GET", "min.php?Mod=IE&EditItem=" + a, true);
    b.send();
}

function ChildStatusUpdate(a) {
    // document.getElementById("childstatus").innerHTML = a;
    $.notific8(a, {
        heading: "Item Editor",
        theme: "ruby",
        life: 3000
    });
}

function IEMusic(a) {
    if (0 == a) document.getElementById("Music").innerHTML = "";
    if (1 == a) document.getElementById("Music").innerHTML = '<embed src="images/001130031538.wav" hidden="true" autostart="true" loop="true" type="application/x-mplayer2"/>';
    var b;
    if (window.XMLHttpRequest) b = new XMLHttpRequest(); else b = new ActiveXObject("Microsoft.XMLHTTP");
    b.onreadystatechange = function() {};
    b.open("GET", "modules/ItemEditor/ajax/ajax.php?SetCookieSetting=Music&Val=" + a, true);
    b.send();
}

function ViewMode(a) {
    var b;
    if (window.XMLHttpRequest) b = new XMLHttpRequest(); else b = new ActiveXObject("Microsoft.XMLHTTP");
    b.onreadystatechange = function() {
        location.reload();
    };
    b.open("GET", "modules/ItemEditor/ajax/ajax.php?SetCookieSetting=ViewMode&Val=" + a, true);
    b.send();
}

function AutoScaler() {
    var a = 1060;
    var b = 740;
    var c = screen.width / 2 - a / 2;
    var d = screen.height / 2 - b / 2;
    window.open("min.php?Mod=IE&AutoScaler=1", "autoscaler", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, height=" + b + ",width=" + a + ", toolbar=no, top=" + d + ", left=" + c);
}

function WriteAutoScalerFields() {
    var a = document.getElementsByClassName("actualfields");
    for (var b = 0, c = a.length; b < c; b++) {
        opener.document.getElementsByName(a[b].id)[0].value = a[b].value;
        opener.document.getElementsByName(a[b].id)[0].setAttribute("style", "border-color: rgba(82, 168, 236, 0.8)");
    }
    ChildStatusUpdate("Values written to parent window");
}

$(document).ready(function() { 
    $("input").change(function() {
        $(this).css("border-color", "rgba(82, 168, 236, 0.8)");
    });
    $("select").change(function() {
        $(this).css("border-color", "rgba(82, 168, 236, 0.8)");
    });
	$(".btn").each(function() { 
        $(this).addClass("btn-xs"); 
    });
});

function getItem(a) {
    var b = false;
    if (document.getElementById) b = document.getElementById(a); else if (document.all) b = document.all[a]; else if (document.layers) b = document.layers[a];
    return b;
}

function toggleItem(a) {
    itm = getItem(a);
    if (!itm) return false;
    if ("none" == itm.style.display) itm.style.display = ""; else itm.style.display = "none";
    return false;
}

var namesVec = new Array("toggleon.png", "toggleoff.png");

var root = "../images/";

function swapImg(a) {
    nr = a.getAttribute("src").split("/");
    nr = nr[nr.length - 1];
    if (nr == namesVec[0]) a.setAttribute("src", root + namesVec[1]); else a.setAttribute("src", root + namesVec[0]);
}

function GetItemSearchForm(a) {
    var b;
    document.getElementById("DataSettings").innerHTML = "<center><br><br><i class='fa fa-spinner fa-spin' style='font-size:80px'></center><br><br>";
    if (window.XMLHttpRequest) b = new XMLHttpRequest(); else b = new ActiveXObject("Microsoft.XMLHTTP");
    b.onreadystatechange = function() {
        if (4 == b.readyState && 200 == b.status) document.getElementById("DataSettings").innerHTML = b.responseText;
    };
    b.open("GET", "../itemsinc.php?ShowSearch=1", true);
    b.send();
}

function GetITFileResult(a) {
    var b;
    document.getElementById("ITFileResult").innerHTML = "<center><br><br><i class='fa fa-spinner fa-spin' style='font-size:80px'></center><br><br>";
    if (window.XMLHttpRequest) b = new XMLHttpRequest(); else b = new ActiveXObject("Microsoft.XMLHTTP");
    b.onreadystatechange = function() {
        if (4 == b.readyState && 200 == b.status) document.getElementById("ITFileResult").innerHTML = b.responseText;
    };
    b.open("GET", "min.php?Mod=IE&prevITfile=1&WeaponType=" + a, true);
    b.send();
}

function EnlargeWeaponImage(a, b) {
    a.src = b;
    a.style.height = "600px";
    a.style.width = "400px";
}

function GetItemEditForm(a) {
    var b;
    document.getElementById("ItemEditForm").innerHTML = "<center><br><br><i class='fa fa-spinner fa-spin' style='font-size:80px'></center><br><br>";
    if (window.XMLHttpRequest) b = new XMLHttpRequest(); else b = new ActiveXObject("Microsoft.XMLHTTP");
    b.onreadystatechange = function() {
        if (4 == b.readyState && 200 == b.status) document.getElementById("ItemEditForm").innerHTML = b.responseText;
    };
    b.open("GET", "extitems3.php?EditItem=" + a, true);
    b.send();
}

$(function() {
    $("img.lazy").lazyload();
});