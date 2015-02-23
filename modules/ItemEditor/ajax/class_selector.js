/**
 * Created by Akkadius on 2/22/2015.
 */

function HighlightField(fieldname) {
    if (document.getElementById(fieldname).checked) {
        document.getElementById(fieldname).checked = false;
        document.getElementById(fieldname).style.filter = "alpha(opacity=50);";
        document.getElementById(fieldname).style.opacity = "0.5";
    }
    else {
        document.getElementById(fieldname).checked = true;
        document.getElementById(fieldname).style.filter = "alpha(opacity=99);";
        document.getElementById(fieldname).style.opacity = "0.99";
    }
    var total = parseInt(0);
    for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
        if (document.getElementById('myform').elements[i].checked) {
            if (document.getElementById('myform').elements[i].value > 0) {
                total += parseInt(document.getElementById('myform').elements[i].value);
            }
        }
    }
    if (total >= 0) {
        opener.document.getElementById("classes").value = total;
        ChildStatusUpdate("<font color=yellow><b>Classes</b></font> set to <font color=#66FF00><b>" + total + "</font></b>");
    }
    else {
        opener.document.getElementById("classes").value = 0;
    }
}
function SetFieldCheckTrue(fieldname) {
    document.getElementById(fieldname).checked = true;
}
function SetFieldCheckFalse(fieldname) {
    document.getElementById(fieldname).checked = false;
}
function checkedAll() {
    for (var i = 1; i <= 16; i++) {
        HighlightField("class" + i);
        HighlightField(i);
    }
}
function CheckClass(Class, Boolean) {
    if (Class) {
        if (Boolean == "true") {
            document.getElementById(Class).checked = true;
            document.getElementById("class" + Class).checked = true;
            document.getElementById("class" + Class).style.filter = "alpha(opacity=99);";
            document.getElementById("class" + Class).style.opacity = "0.99";
        }
        else {
            document.getElementById(Class).checked = false;
            document.getElementById("class" + Class).checked = false;
            document.getElementById("class" + Class).style.filter = "alpha(opacity=50);";
            document.getElementById("class" + Class).style.opacity = "0.5";
        }
    }
}
function CheckAll(Type) {
    if (Type == "Leather") {
        if (document.getElementById("Leather").checked) {
            CheckClass("15", "true");
            CheckClass("7", "true");
            CheckClass("6", "true");
        }
        else {
            CheckClass("15", "false");
            CheckClass("7", "false");
            CheckClass("6", "false");
        }
    }
    if (Type == "Plate") {
        if (document.getElementById("Plate").checked) {
            CheckClass("1", "true");
            CheckClass("2", "true");
            CheckClass("3", "true");
            CheckClass("5", "true");
            CheckClass("8", "true");
        }
        else {
            CheckClass("1", "false");
            CheckClass("2", "false");
            CheckClass("3", "false");
            CheckClass("5", "false");
            CheckClass("8", "false");
        }
    }
    if (Type == "Chain") {
        if (document.getElementById("Chain").checked) {
            CheckClass("4", "true");
            CheckClass("9", "true");
            CheckClass("10", "true");
            CheckClass("16", "true");
        }
        else {
            CheckClass("4", "false");
            CheckClass("9", "false");
            CheckClass("10", "false");
            CheckClass("16", "false");
        }
    }
    if (Type == "Silk") {
        if (document.getElementById("Silk").checked) {
            CheckClass("12", "true");
            CheckClass("13", "true");
            CheckClass("14", "true");
            CheckClass("11", "true");
        }
        else {
            CheckClass("12", "false");
            CheckClass("13", "false");
            CheckClass("14", "false");
            CheckClass("11", "false");
        }
    }
    if (Type == "All") {
        if (document.getElementById("All").checked) {
            document.getElementById("Silk").checked = true;
            document.getElementById("Leather").checked = true;
            document.getElementById("Plate").checked = true;
            document.getElementById("Chain").checked = true;
            for (var i = 0; i <= 16; i++) {
                CheckClass(i, "true");
            }
        }
        else {
            document.getElementById("Silk").checked = false;
            document.getElementById("Leather").checked = false;
            document.getElementById("Plate").checked = false;
            document.getElementById("Chain").checked = false;
            for (var i = 0; i <= 16; i++) {
                CheckClass(i, "false");
            }
        }
    }
    var total = parseInt(0);
    for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
        if (document.getElementById('myform').elements[i].checked) {
            if (document.getElementById('myform').elements[i].value > 0) {
                total += parseInt(document.getElementById('myform').elements[i].value);
            }
        }
    }
    if (total >= 0) {
        opener.document.getElementById("classes").value = total;
        ChildStatusUpdate("<font color=yellow><b>Classes</b></font> set to <font color=#66FF00><b>" + total + "</font></b>");
    }
    else {
        opener.document.getElementById("classes").value = 0;
    }
}